<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Categories;
use Illuminate\Support\Facades\Auth;
use DB;
use Intervention\Image\ImageManager;

class ItemsController extends Controller {
    /**
     * Permet d'ajouter un Item dans la base de données
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request) {
        /*
         * Validation des champs sous la forme :
         *  Nom du champ => règles de validation
         */
        $this->validate($request, [
            'name' => 'required|max:200',
            'category' => 'required|in:' . implode(',', Categories::getSlugs()),
            'photo' => 'required|image',
            'price' => 'required|numeric',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            'description' => 'required',
        ]);

        // S'il y a eu un problème quelconque pendant la création de l'Item
        if(!($id = $this->create($request))) {
            $request->session()->flash('message', 'danger|Une erreur s\'est produite');
            return redirect('sell::index');
        }

        // Tout s'est bien passé, on redirige ensuite sur la page de l'annonce
        $request->session()->flash('message', 'success|Votre annonce à bien été mise en ligne !');
        return redirect(route('item', ['id' => $id]));
    }

    /**
     * Permet de créer un Item dans la base de données.
     * Compresse l'image envoyée par l'utilisateur, et crée aussi une miniature
     * @param Request $request
     * @return bool|mixed
     */
    public function create(Request $request) {
        // Récupération des données envoyées par l'utilisateurs
        $datas = $request->all();
        // Création de l'ImageManager, classe qui permet de manipuler les images
        $manager = new ImageManager(['driver' => 'imagick']);

        // Création de l'Item
        $item = new Items();
        $item->user_id = Auth::user()->id;
        $item->category_id = Categories::where('slug', $datas['category'])->value('id');
        $item->name = $datas['name'];
        $item->description = $datas['description'];
        $item->minimum_price = $datas['price'];
        $item->date_start = $datas['date_start'];
        $item->date_end = $datas['date_end'];

        // Récupération de la photo envoyée par l'utilisateur
        $file = $request->file('photo');
        $filename = str_limit($file->getClientOriginalName(), 40) . str_random();
        $filename_thumb = $filename . '_thumb';

        // Sauvegarde de la photo
        $file->move(public_path('upload'), $filename . '.' . $file->getClientOriginalExtension());

        $filename = public_path('upload') . '/' . $filename;
        $filename_thumb = public_path('upload') . '/' . $filename_thumb;

        // Active l'entrelacement sur la photo envoyée par l'utilisateur et réduit la qualité
        $manager->make($filename . '.' . $file->getClientOriginalExtension())
            ->interlace(true)
            ->save($filename . '.jpg', 80);

        // Si par exemple l'utilisateur a envoyé un .png (ou un .Jpg), alors on supprime l'image,
        // puisqu'on a déjà sauvegardé une version en .jpg
        if($file->getClientOriginalExtension() !== 'jpg') {
            unlink($filename . '.' . $file->getClientOriginalExtension());
        }

        // Création de la miniature, de taille 400px * ratio px.
        $manager->make($filename . '.jpg')
                ->resize(400, null, function($constraint) {
                    $constraint->aspectRatio();
                })
                ->interlace(true)
                ->save($filename_thumb . '.jpg');

        // Si on a bien réussi à sauvegarder l'Item, on renvoie l'id, pour une redirection sur la page de l'Item
        if($item->save()) {
            return $item->id;
        }

        // Sinon il y a eu un problème. :<
        return false;
    }
}
