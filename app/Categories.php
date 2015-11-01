<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model {
    /**
     * @return array
     */
    public static function getCategories() {
        $categories = DB::table('categories')->get(['name', 'slug']);
        $categoriesToReturn = [];

        foreach($categories as $category) {
            $categoriesToReturn[$category->slug] = $category->name;
        }

        return $categoriesToReturn;
    }
}
