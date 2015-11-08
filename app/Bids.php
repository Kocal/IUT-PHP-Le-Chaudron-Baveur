<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bids extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    // Champs à remplir dans la bdd
    protected $fillable = ['user_id', 'item_id', 'product_id', 'price'];

    /**
     * Fait la relation entre une enchère et son "auteur"
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('\App\User');
    }

    /**
     * Fait la relation entre une enchère et son item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function items() {
        return $this->belongsTo('\App\Items', 'item_id', 'id', '=');
    }
}
