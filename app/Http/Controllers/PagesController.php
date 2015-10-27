<?php

namespace App\Http\Controllers;

use App\Bids;
use App\Http\Requests;

class PagesController extends Controller {

    public function index() {
        $maxBid = Bids::orderBy('price', 'desc')->first();

        return view('index');
    }
}
