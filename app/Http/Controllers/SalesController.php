<?php

namespace App\Http\Controllers;

use DB;
use App\Categories;
use App\Http\Requests;

class SalesController extends Controller {

    /**
     * Correspond à la route GET "/sale"
     *
     * Retourne la View 'sell' en passant les Categories en paramètres
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $categories = Categories::all();
        $categoriesForView = [];

        foreach($categories as $category) {
            $categoriesForView[$category->slug] = $category->name;
        }

        // Tri des clés par ordre alphabétique
        ksort($categoriesForView);

        return view('sell')->with('categories', $categoriesForView);
    }
}
