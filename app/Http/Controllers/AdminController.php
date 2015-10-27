<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller {
    /**
     * Supprime les annonces trop anciennes
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function refine(Request $request) {
        $request->session()->flash('message', 'success|Les annonces trop anciennes ont été purgées avec succès !');



        die();
        return redirect('admin');
    }
}
