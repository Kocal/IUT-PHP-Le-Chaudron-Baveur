<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /**
     * @var array Champs qui constituent un Item
     */
    public $fillable = [
        'user_id',
        'name', 'photo',  'category_id',
        'price', 'date_start', 'date_end',
        'description'];
}
