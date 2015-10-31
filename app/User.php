<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'phone', 'pseudo', 'email', 'password', 'address', 'user_type_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    /**
     * Un utilisateur possède plusieurs annonces
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany('\App\Items');
    }

    /**
     * Un utilisateur possède plusieurs enchères
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids() {
        return $this->hasMany('\App\Bids');
    }

    /**
     * Retourne les ventes toujours en ligne de l'utilisateur
     *
     * @return mixed
     */
    public function getOnlineItems() {
        return $this
            ->items()
            ->where('date_start', '<=', date('Y-m-d'))
            ->where('date_end', '>', date('Y-m-d'))
            ;
    }

    /**
     * Retourne les enchères de l'utilisateur qui sont toujours en cours
     *
     * @return mixed
     */
    public function getOnlineBids() {
        return $this
            ->bids()
            ->groupBy('user_id');
    }

    public function getHashedCredentials() {
        return md5(
            $this->first_name . '&&' .
            $this->last_name . '&&' .
            $this->email . '&&' .
            $this->pseudo
        );
    }

    public function getHashedDeletedAt() {
        return md5($this->deleted_at);
    }

    public function isAdmin() {
        return $this->user_type_id === '1';
    }
}
