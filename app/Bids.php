<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bids extends Model {

    // Champs à remplir dans la bdd
    protected $fillable = ['user_id', 'item_id', 'product_id', 'price'];

}
