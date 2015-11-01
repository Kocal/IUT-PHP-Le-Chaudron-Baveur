<?php

namespace App\Http\Controllers;

use DB;
use App\Categories;
use App\Http\Requests;

class SalesController extends Controller {
    public function index() {
        $categories = Categories::getSlugs();

        return view('sell')
               ->with(compact('categories'));
    }
}
