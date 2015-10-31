<?php

namespace App\Http\Controllers;

use App\Sales;
use App\Bids;
use App\Http\Requests;
use App\User;

class PagesController extends Controller {

    public function index() {
        $maxSale = Sales
            ::addSelect('items.id AS id','items.name AS name', 'bids.price AS price', 'bids.user_id AS buyer_id', 'items.user_id AS seller_id')
            ->join('bids', 'sales.bid_id', '=', 'bids.id')
            ->join('items', 'bids.item_id', '=', 'items.id')
            ->orderBy('bids.price', 'desc')
            ->first()
        ;

        $buyer = User::withTrashed()->find($maxSale->buyer_id);
        $seller = User::withTrashed()->find($maxSale->seller_id);

        return view('index')
            ->with(compact('maxSale', 'buyer', 'seller'));
    }
}
