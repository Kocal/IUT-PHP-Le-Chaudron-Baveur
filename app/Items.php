<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /**
     * @var array Champs qui constituent un Item
     */
    public $fillable = [
        'user_id', 'category_id',
        'name', 'description', 'photo_url', 'minimum_price',
        'date_start', 'date_end'];
}
