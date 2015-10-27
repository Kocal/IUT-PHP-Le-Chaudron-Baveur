<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Items extends Model {

    use SoftDeletes;

    /**
     * @var array Champs qui constituent un Item
     */
    public $fillable = [
        'user_id',
        'name', 'photo',  'category_id',
        'price', 'date_start', 'date_end',
        'description'];

    /**
     * @var array Permet d'utiliser le softDelete
     */
    protected $dates = ['deleted_at'];

    /**
     * Retourne les informations sur le vendeur de l'enchère
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Retourne les informations sur la catégorie associée à l'enchère
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Categories');
    }

    /**
     * Retourne les enchères associées à la vente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids() {
        return $this->hasMany('\App\Bids', 'item_id');
    }

    /**
     * Détermine si l'utilisateur connecté est le vendeur ou non de l'annonce
     *
     * @return bool
     */
    public function isSeller() {
        return Auth::check() && Auth::user()->id == $this->user_id;
    }

    /**
     * Retourne la différence entre la date de fin de l'enchère et aujourd'hui
     *
     * @return int timestamp
     */
    public function getDateDiff() {
        return strtotime($this->date_end) - time();
    }

    /**
     * Retourne soit le montant de la dernière enchère, soit le montant initial de la vente
     *
     * @return mixed
     */
    public function getPrice() {
        $bids = $this->bids();
        $higherBid = $bids->select('price')->orderBy('price', 'desc')->first();

        if($higherBid === null) {
            return $this->price;
        }

        return $higherBid->price;
    }

    /**
     * Retourne le nombre d'essais d'enchère que l'utilisateur a déjà fait sur l'annonce en cours
     *
     * @return int
     */
    public function getUserBidsCount() {
        return Auth::check() ? $this->bids()->where('user_id', Auth::user()->id)->count('id') : 0;
    }

    /**
     * Détermine si l'utilisateur peut renchérir cette annonce ou non
     *
     * @return bool
     */
    public function getUserCantBid() {
        return Auth::Check() && $this->userBidsCount >= MAX_BID_PER_SALE;
    }

}
