<?php

namespace App\Http\Controllers;

use App\Bids;
use App\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class BidsController extends Controller {

    /**
     * Vérification des données utilisateurs avant la création de l'enchère
     *
     * @param Request $request
     * @param int $item_id Identifiant de l'item
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request, $item_id) {
        // Après quelques vérifications, l'utilisateur saura s'il a le droit d'enchérir ou non
        $denied = false;

        // On récupère l'ID du formulaire (permettra de savoir à quel formulaire une erreur s'est produite)
        $form_id = $request->input('_form_id', 'default');

        // Si l'utilisateur a supprimé le champ (gg), on essaye de récupérer l'ID dans l'URL
        if($form_id === null && preg_match('/\/items/', URL::previous())) {
            $url_items = explode('/', $request->getPathInfo());
            $form_id = 'form_' . array_pop($url_items);
        }

        // Les messages d'erreurs seront désormais spécifiques à un formulaire, et pas tous
        $this->validatesRequestErrorBag = $form_id;
        // Permettra de savoir dans quel formulaire s'est produite l'erreur
        $request->session()->flash('errorBag', $this->validatesRequestErrorBag);

        // On check si l'item existe bien dans la BBD ('Ivre, il enchérit une vente qui n'existe pas, la suite va vous surprendre !)
        $item = Items::get()->where('id', $item_id)->first();
        $min_price = $item->getPrice() + 1;

        // L'enchère n'existe pas
        if($item === null) {
            $denied = true;
            $request->session()->flash('message', 'danger|Cette enchère n\'existe pas');
            return redirect(route('items'));
        }

        // L'enchère existe, donc on fait quelques tests dessus
        if($item !== null) {
            // La vente n'a pas encore commencé
            if(strtotime($item->date_end) - time() < 0) {
                $denied = true;
                $request->session()->flash('message', 'danger|L\'enchère n\'a même pas commencé, calmez-vous...');
            // La vente est terminé
            } elseif(strtotime($item->date_start) - time() > 0) {
                $denied = true;
                $request->session()->flash('message', 'danger|L\'enchère est terminée !');
            }

            // L'utilisateur a atteint le nombre maximum de renchère sur cette annonce
            if($item->getBidCountByUserId(Auth::user()->id) >= MAX_BID_PER_SALE) {
                $denied = true;
                $request->session()->flash('message', 'danger|Vous avez dépassé les ' . MAX_BID_PER_SALE . ' propositions d\'enchères maximales !');
            }
        }

        // Le vendeur ne peut pas enchérir sa propre annonce...
        if($item->isSeller()) {
            $denied = true;
            $request->session()->flash('message', 'danger|Il n\'est pas possible d\'enchérir votre annonce..');
        }

        // Pour une quelconque raison, l'utilisateur n'a pas pu enchérir
        if($denied) {
            return redirect(route('items'));
        }

        // Le prix rentré doit être supérieur à la valeur minimale de l'enchère
        $this->validate($request, [
            'price' => 'required|numeric|min:' . $min_price
        ], [
            'price.min' => 'Le prix doit être supérieur à :min €.'
        ]);

        return $this->create($request, $item_id);
    }

    /**
     * Insertion d'une nouvelle enchère dans la BDD
     *
     * @param Request $request
     * @param int $item_id Identifiant de l'item
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request, $item_id) {
        $datas = $request->all();

        // Création d'une nouvelle enchère
        $bid = new Bids();
        $bid->user_id = Auth::user()->id;
        $bid->item_id = $item_id;
        $bid->price = trim($datas['price']);

        if($bid->save()) {
            $request->session()->flash('message', 'success|Votre enchère a bien été saisie !');
        } else {
            $request->session()->flash('message', 'error|Il s\'est passé un truc, dsl');
        }

        return redirect(URL::previous());
    }
}
