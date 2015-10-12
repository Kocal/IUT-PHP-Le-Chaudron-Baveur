<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bids extends Model
{
    //

    public static function getBidPriceOrProductPrice($id) {
        $bid = Bids::get(['price'])->where('product_id', $id)->last();

        if($bid !== null) {
            return $bid->price;
        }

        return Items::get(['id', 'price'])->where('id', $id)->last()->price;
    }
}
