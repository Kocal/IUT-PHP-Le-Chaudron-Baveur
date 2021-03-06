<?php

namespace App\Http\Controllers;

use DB;
use App\Categories;
use App\Http\Requests;

class SalesController extends Controller {
    public function index() {
        $categories = Categories::getCategories();

        return view('sell')
               ->with(compact('categories'));
    }
}
