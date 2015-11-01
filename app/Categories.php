<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model {
    /**
     * Permet de retourner la liste des "slug" des Catégories
     * @return array
     */
    public static function getSlugs() {
        $slugs = DB::table('categories')->get(['slug']);
        $slugsToReturn = [];

        foreach($slugs as $slug) {
            $slugsToReturn[] = $slug->slug;
        }

        return $slugsToReturn;
    }
}
