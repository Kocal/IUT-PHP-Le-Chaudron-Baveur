<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bids extends Model {

    // Champs à remplir dans la bdd
    protected $fillable = ['user_id', 'product_id', 'price'];

    /**
     * Récupère le prix de la dernière enchère, s'il n'y a pas eu d'enchère, récupère alors le prix de mise en vente
     * @param int $id Identifiant de la vente
     * @return float mixed Montant
     */
    public static function getLastBidPriceOrProductPrice($id) {
        $bid = Bids::get(['item_id', 'price'])->where('item_id', $id)->last();

        if($bid !== null) {
            return $bid->price;
        }

        $item = Items::get(['id', 'price'])->where('id', $id)->last();

        if($item !== null) {
            return $item->price;
        }

        return null;
    }
}
