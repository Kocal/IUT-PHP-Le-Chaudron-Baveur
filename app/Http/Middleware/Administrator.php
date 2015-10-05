<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Administrator
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($this->auth->check()) {
            if ($this->auth->user()->user_type_id === '1') {
                return $next($request);
            } else {
                $request->session()->flash('message', 'danger|Vouns n\'êtes pas administrateur. ^^=))');
            }
        } else {
            $request->session()->flash('message', 'danger|Vous devez être connecté pour accéder à cette page');
        }

        return redirect('/');
    }
}
