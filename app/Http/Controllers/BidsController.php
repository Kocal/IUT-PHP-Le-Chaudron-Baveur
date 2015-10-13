<?php

namespace App\Http\Controllers;

use App\Bids;
use App\Items;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class BidsController extends Controller {

    /**
     * Fait quelques vérifications aux niveaux des données envoyés par l'utilisateur, puis ajoute une enchère dans le bdd
     * @param Request $request
     * @param $item_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request, $item_id) {

        $form_id = $request->input('_form_id', 'default');

        // Si on est sur la page qui liste les ventes, et l'utilisateur trafique le code HTML de la page
        if($form_id === null && preg_match('/\/items/', URL::previous())) {
            $url_items = explode('/', $request->getPathInfo());
            $form_id = 'form_' . array_pop($url_items);
        }

        $this->validatesRequestErrorBag = $form_id;

        $item = Items::get()->where('id', $item_id)->first();
        $min_price = Bids::getLastBidPriceOrProductPrice($item_id);

        if($item === null || $min_price === null) {
            $request->session()->flash('message', 'danger|Cette enchère n\'existe pas');
            return redirect(route('items'));
        }

        $seller_email = User::getEmailById($item->user_id);

        if(strtolower($seller_email) === strtolower(Auth::user()->email)) {
            $request->session()->flash('message', 'danger|Il n\'est pas possible d\'enchérir votre annonce');
            return redirect(route('items'));
        }

        $this->validate($request, [
            'price' => 'required|numeric|min:' . $min_price
        ]);

        return $this->create($request, $item_id);
    }

    /**
     * @param Request $request
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request, $item_id) {
        $datas = $request->all();

        $bid = new Bids();
        $bid->user_id = Auth::user()->id;
        $bid->item_id = $item_id;
        $bid->price = trim($datas['price']);

        if($bid->save()) {
            $request->session()->flash('message', 'success|Votre enchère a bien été saisie !');
        } else {
            $request->session()->flash('message', 'error|Il s\'est passé un truc, dsl');
        }
//        return redirect(route('item', ['id' => $item_id]));
        return redirect(URL::previous());
    }
}
