<?php

namespace App\Http\Controllers;

use App\Bids;
use App\Items;
use App\Sales;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller {

    public function index(Request $request) {

        $items = $this->getItemsToHandle();

        foreach($items as $item) {
            $item->lastBid = $item->bids->last();
            $item->gotBid = $item->lastBid !== null;
        }

        return view('admin.index')
            ->with(compact('items'));
    }

    /**
     * Nettoyage de la BDD
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function refine(Request $request) {
        $items = $this->getItemsToHandle();

        $logs = [];

        foreach($items as $item) {
            $logs[$item->id] = $this->handleItem($item);
        }

        return redirect('admin')
            ->with(compact('logs'));
    }

    /**
     * Permet de récupérer plus simplement les annonces qui viennent de se terminer
     *
     * @return mixed
     */
    public function getItemsToHandle() {
        return Items
            ::where('date_end', '<=',  date('Y-m-d'))
            ->with('user', 'bids')
            ->get();
    }

    /**
     * Traite un item
     *
     * @param \App\Items $item Item à traiter
     * @return array Logs
     */
    public function handleItem($item) {
        $logs = [];

        // Dernière enchère de l'annonce
        $lastBid = $item->bids->last();

        // Est-ce qu'on a au moins eu une enchère ?
        if ($lastBid !== null) {
            $logs[] = $item->user->pseudo . ' a vendu à ' . $lastBid->user->pseudo . '.';
            $this->sendMailForSell($item->user, $lastBid->user, $item, $lastBid);

            // La vente a bien été effectuée ! :-)
            $sale = new Sales();
            $sale->bid_id = $lastBid->id;
            $sale->save();

            // Suppression des enchères de l'utilisateur sur l'annonce en cours
            $lastBid->user->bids()->where('item_id', $item->id)->delete();
            $logs[] = $this->handleBuyer($lastBid->user);
        } else {
            $logs[] = $item->user->pseudo . ' n\'a pas réussi à vendre.';
            $this->sendMailForNotSell($item->user, $item);
        }

        $item->delete();
        $logs[] = $this->handleSeller($item->user);
        return $logs;
    }

    /**
     * @param \App\User $seller
     * @return string
     */
    public function handleSeller($seller) {
        if(!$seller->isAdmin() && $seller->getOnlineItems()->count() === 0 && $seller->getOnlineBids()->distinct('item_id')->count() === 0) {
            $seller->delete();

            Mail::send(['emails.sellerDisabledAccount-html', 'emails.sellerDisabledAccount-text'], compact('seller'), function($message) use ($seller) {
                $message
                    ->to($seller->email, $seller->pseudo)
                    ->subject('Votre compte a été désactivé (' . $seller->pseudo .')');
            });

            return 'Le compte du vendeur ' . $seller->pseudo . ' a été désactivé.';
        }
    }

    /**
     * @param \App\User $buyer Acheteur qui vient de remporter une enchère
     * @return string
     */
    public function handleBuyer($buyer) {
        if(!$buyer->isAdmin() && $buyer->getOnlineBids()->distinct('item_id')->count() === 0 && $buyer->getOnlineItems()->count() === 0) {
            $buyer->delete();

            Mail::send(['emails.buyerDisabledAccount-html', 'emails.buyerDisabledAccount-text'], compact('buyer'), function($message) use($buyer) {
                $message
                    ->to($buyer->email, $buyer->pseudo)
                    ->subject('Votre compte a été désactivé (' . $buyer->pseudo .')');
            });

            return 'Le compte de l\'acheteur ' . $buyer->pseudo . ' a été désactivé.';
        }
    }

    /**
     * Envoie un mail au vendeur et à l'acheteur quand une vente a été faite
     *
     * @param \App\User $seller Vendeur
     * @param \App\User $buyer Acheteur
     * @param \App\Item $item Item en vente
     * @param \App\Bids $bid Enchère "gagnante"
     */
    public function sendMailForSell($seller, $buyer, $item, $bid) {
        Mail::send(['emails.sellerSuccessfulSell-html', 'emails.sellerSuccessfulSell-text'],
            compact('seller', 'buyer', 'item', 'bid'), function($message) use ($seller, $item) {
            $message
                ->to($seller->email, $seller->pseudo)
                ->subject('Votre annonce a été vendue !');
        });

        Mail::send(['emails.buyerSuccessfulPurchase-html', 'emails.buyerSuccessfulPurchase-text'],
            compact('seller', 'buyer', 'item', 'bid'), function($message) use ($buyer, $item) {
            $message
                ->to($buyer->email, $buyer->pseudo)
                ->subject('Vous avez remporté l\'annonce #' . $item->id . ' !');
        });
    }

    /**
     * Envoie un mail au vendeur pour lui dire que sa vente n'a rien donné
     * @param \App\\User $seller
     * @param \App\Items $item
     */
    public function sendMailForNotSell($seller, $item) {
        Mail::send(['emails.sellerFailSell-html', 'emails.sellerFailSell-text'], compact('seller', 'item'), function($message) use ($seller, $item) {
            $message
                ->to($seller->email, $seller->pseudo)
                ->subject('Votre annonce n\'a pas été vendue.');
        });
    }
}
