<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Items extends Model {
    
    /**
     * @var array Champs qui constituent un Item
     */
    public $fillable = [
        'user_id',
        'name', 'photo',  'category_id',
        'price', 'date_start', 'date_end',
        'description'];

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
     * Retourne la différence entre la date de fin de l'enchère et aujourd'hui
     *
     * @return int timestamp
     */
    public function getDateDiff() {
        return strtotime($this->date_end) - time();
    }

    /**
     * Retourne le nombre d'essais effectués sur une enchère par l'utilisateur $user_id
     *
     * @param int $user_id Identifiant de l'utilisateur à tester
     * @return int Nombre d'essais effectués sur une enchère par l'utilisateur
     */
    public function getBidCountByUserId($user_id) {
        return Bids
            ::where('user_id', $user_id)
            ->where('item_id', $this->id)
            ->count();
    }

    /**
     * Retourne le montant de la dernière enchère effectuée sur cette vente.
     * S'il n'y a eu aucune enchère sur l'annonce, alors on retourne le prix initial de l'annonce.
     *
     * @return float Un Prix
     */
    public function getPrice() {
        return Bids::getLastBidPriceOrProductPrice($this->id);
    }

    /**
     * Détermine si l'utilisateur connecté est le vendeur ou non de l'annonce
     *
     * @return bool
     */
    public function isSeller() {
        return Auth::check() && Auth::user()->id == $this->user_id;
    }
}
