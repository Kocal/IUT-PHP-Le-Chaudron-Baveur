<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;
    use ThrottlesLogins;

    /**
     * @var string URL de redirection après authentification
     */
    private $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone_number' => 'required',
            'adress' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $data['phone_number'] = str_replace(['.', ' ', '-'], '', $data['phone_number']);
        $data['phone_number'] = preg_replace('/\+[0-9]{2}(.+)/', '0$1', $data['phone_number']);

        // Premier utilisateur = admin
        $data['user_type_id'] = (User::first() === null) ? 1 : 2;

        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'user_type_id' => $data['user_type_id'],
            'adress' => $data['adress'],
            'password' => bcrypt($data['password'])
        ]);
    }

    /**
     * Affiche un message après une connexion réussie
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request) {
        $request->session()->flash('message', 'success|Connexion réussie');
        return redirect()->intended($this->redirectPath());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));
        $request->session()->flash('message', 'sucess|Inscription réussie');

        return redirect($this->redirectPath());
    }
}
