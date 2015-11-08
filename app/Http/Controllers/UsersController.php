<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {

    /**
     * Affiche la page de profil
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('profile', [
            'items' => Auth::user()->items()->get(),
            'bids' => Auth::user()->bids()->with('items')->get(),
            'credentials_hash' => Auth::user()->getHashedCredentials()
        ]);
    }

    /**
     * Permet de ré-activer un compte
     *
     * @param Request $request
     * @param int $user_id Identifiant utilisateur
     * @param string $credentials_hash Hash des credentials de l'utilisateur
     * @param string $deleted_at_hash Hash de la date de la désactivation du compte
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable(Request $request, $user_id, $credentials_hash, $deleted_at_hash) {
        // Oui, c'est sale, mais c'est pour éviter de dire à un utilisateur malveillant que le compte n'existe pas, existe,
        // que c'est le hash des credentials qui est mauvais, ou que c'est le hash du deleted_at qui est mauvais
        // Au final, s'il y a une étape qui a merdée, on lui dit juste que la réactivation était impossible. :-)
        $error = false;

        // On n'oublie pas le withTrashed(), sinon ça ne renvoie rien
        $user = User::withTrashed()->find($user_id);

        if($user === null) {
            $error = true;
        }

        if(!$error && (
                $credentials_hash !== $user->getHashedCredentials() ||
                $deleted_at_hash !== $user->getHashedDeletedAt()
            )
        ) {
            $error = true;
        }

        if($error) {
            $request->session()->flash('message', 'danger|La réactivation de ce compte est impossible.');
        } else {
            // Réactivation du compte
            $user->deleted_at = null;
            $user->update();

            Auth::loginUsingId($user->id);

            $request->session()->flash('message', 'success|Votre compte a été réactivé avec succès !');
        }

        return redirect(route('index'));
    }

    /**
     * @param Request $request
     * @param $credentials_hash
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request, $credentials_hash) {

        // Validation des données envoyées par l'utilisateur
        $this->validate($request, [
            'password' => 'required'
        ]);

        // On récupère l'utilisateur courant
        $user = Auth::user();

        // On check les différents hashs des certificats
        if($credentials_hash === $user->getHashedCredentials()) {

            // On check si les mots de passent correspondent bien
            if(Hash::check($request->get('password'), $user->password)) {
                // Au lieu de supprimer le compte et "baiser" nos relations avec les autres tables, on modifie les différentes
                // informations de l'utilisateur en "Compte supprimé"
                // C'est clair que c'est une manière bien dégueulasse de faire ça, mais j'aurais du y penser avant.
                // J'ai un peu la flemme de modifier toutes les vues.
                // En fait, au lieu d'utiliser `$user->email` pour récupérer son e-mail, j'aurais du créer une méthode
                // qui me renvoit soit son e-mail, soit "Compte supprimé" si son compte avait été supprimé.
                // Au moins j'aurais quelquechose... :^)
                $user->first_name =
                $user->last_name =
                $user->pseudo =
                $user->address =
                $user->phone =
                    'Compte supprimé';

                $user->password = '';
                $user->email =
                $user->remember_token = null;


                // Suppression des annonces (safeDelete)
                $user->items()->delete();

                // Suppression des enchères (safeDelete)
                $user->bids()->delete();

                $user->update();
                $user->delete(); // safeDelete
                $request->session()->flash('message', 'success|Votre compte a bien été supprimé !');
                return redirect(route('index'));
            } else {
                $request->session()->flash('message', 'danger|Le mot de passe que vous avez rentré ne correspond pas au mot de passe de votre compte');
                return redirect(URL::previous());
            }
        }
        else {
            Auth::logout();
            $request->session()->flash('message', 'danger|?????????????????? :-))))))) ffs fgt');
            return redirect(route('index'));
        }

        die();
    }
}
