<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Permet de valider les données envoyées par l'utilisateur pendant l'inscription
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'            => 'required|max:255',
            'last_name'             => 'required|max:255',
            'email'                 => 'required|email|max:255|unique:users',
            'phone'                 => 'required',
            'address'               => 'required',
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);
    }

    /**
     * Permet de créer un nouvel User dans la base de données, après que la validation s'est bien passée
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // Si l'utilisateura rentré un numéro de téléphone du genre "01.12-23.12.54"
        $data['phone'] = str_replace(['.', ' ', '-'], '', $data['phone']);
        $data['phone'] = preg_replace('/\+[0-9]{2}(.+)/', '0$1', $data['phone']);

        // Si on n'a pas encore d'utilisateurs, alors ce nouvel utilisateur est Admin, sinon il est Particulier
        $data['user_type_id'] = (User::first() === null) ? 1 : 2;

        // Génération d'un pseudo unique, puisque les comptes peuvent être supprimé
        $data['pseudo'] = strtolower(substr($data['last_name'], 0, 1) . substr($data['first_name'], 0, 2) . '.' . substr($data['email'], 0, 3) . '.' . str_random(9));

        // Création de l'utilisateur dans la base de données
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'pseudo' => $data['pseudo'],
            'phone' => $data['phone'],
            'user_type_id' => $data['user_type_id'],
            'address' => $data['address'],
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
     * Affiche un message après une inscription réussie
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(Request $request) {
        // Validation des données du formulaire d'inscription
        $validator = $this->validator($request->all());

        // Si il y a eu un problème, on lance une ValidationException.
        // Retourne sur la page d'inscription, et affiche les différentes erreurs pour les différents champs
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        // Tout c'est bien passé, on créer l'utilisateur dans la base de données, et on le connecte au site
        Auth::login($this->create($request->all()));
        $request->session()->flash('message', 'success|Inscription réussie');

        // Redirection vers $this->redirectTo
        return redirect($this->redirectPath());
    }

    /**
     * Déconnexion de l'utilisateur
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout(Request $request) {
        Auth::logout();
        $request->session()->flash('message', 'success|Vous vous êtes bien déconnecté !');
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
