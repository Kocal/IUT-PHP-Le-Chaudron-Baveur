<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model {

    public $fillable = ['bid_id'];

    public function bids() {
        return $this->belongsTo('\App\Bids');
    }
}
