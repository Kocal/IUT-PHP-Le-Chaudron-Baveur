<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
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

    /**
     * Permet de récupérer le nom d'une catégorie via son identifiant
     * @param int $id Identifiant de catégorie
     * @return string|null Nom de la catégorie ou rien
     */
    public static function getNameById($id) {
        $category = Categories::get(['id', 'name'])->where('id', $id)->first();

        if($category !== null) {
            return $category->name;
        }

        return null;
    }
}
