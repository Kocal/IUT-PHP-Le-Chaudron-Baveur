<?php

namespace App\Http\Controllers;

use DB;
use App\Categories;
use App\Http\Requests;
use App\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;

setlocale(LC_ALL, 'fr_FR.UTF-8');

class ItemsController extends Controller {

    /**
     * @var array Contient les différentes façons de trier la liste des items sur la page des ventes
     */
    public $sortOptionsDefinitions;

    public function __construct() {
        $this->sortOptionsDefinitions = [
            route('items_sort', ['type' => 'date_start', 'sort' => 'desc']) => 'Nouvelles ventes',
            route('items_sort', ['type' => 'duration', 'sort' => 'asc']) => 'Durée (ventes se terminant)',

            route('items_sort', ['type' => 'price_open', 'sort' => 'asc']) => 'Prix d\'entrée (les moins chers)',
            route('items_sort', ['type' => 'price_open', 'sort' => 'desc']) => 'Prix d\'entrée (les plus chers)',

            route('items_sort', ['type' => 'price_last_bid', 'sort' => 'asc']) => 'Prix de dernière enchère (les moins chers)',
            route('items_sort', ['type' => 'price_last_bid', 'sort' => 'desc']) => 'Prix de dernière enchère (les plus chers)',

            route('items_sort', ['type' => 'categories', 'sort' => 'asc']) => 'Catégories (A à Z)',
            route('items_sort', ['type' => 'categories', 'sort' => 'desc']) => 'Catégories (Z à A)',
        ];
    }

    /**
     * Retourne un tableau des enchères en cours
     *
     * @param Request $request
     * @return $this
     */
    public function index(Request $request) {
        // Pagination de 30 enchères par page
        $items = Items
            ::where('date_start', '<=',  date('Y-m-d'))
            ->where('date_end', '>',  date('Y-m-d'))
            ->with('category', 'user', 'bids')
            ->paginate(20);

        foreach($items as $item) {
            $bids = $item->bids();

            // On récupère soit le montant de la dernière enchère, soit le montant initial de l'annonce
            $item->lastBidPrice = $item->getPrice();

            // Détermine si l'utilisateur connecté est le vendeur de l'annonce
            $item->userIsSeller = $item->isSeller();

            // Compte le nombre de propositions de l'utilisateur sur l'enchère
            $item->userBidsCount = $item->getUserBidsCount();

            // L'utilisateur sera incapable de renchérir s'il a dépassé le montant maximum d'essais par vente
            $item->userCantBid = Auth::Check() && $item->userBidsCount >= MAX_BID_PER_SALE;

            // Prix minimum de la prochaine renchère (rajouter 0,01 € serait abusé...)
            $item->minBidPrice = $item->lastBidPrice + 1;

            // Identifiant du formulaire, utile pour mettre en évidence le formulaire où une erreur s'est produite
            $item->formId = 'form_' . $item->id;

            $item->dateDifference = $item->getDateDiff();
        }

        return view('items')
            ->with('items', $items)
            ->with('sortOptionsDefinitions', $this->sortOptionsDefinitions);
    }

    /**
     * Retourne de plus amples informations sur un objet mis en vente
     *
     * @param Request $request
     * @param int $item_id Identifiant de l'item
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function see(Request $request, $item_id) {
        $item = Items
            ::select([
                'id', 'user_id', 'category_id',
                'name', 'description', 'photo', 'price',
                'date_start', 'date_end'])
            ->withTrashed()
            ->where('id', $item_id)
            ->first();

        $isSeller = $item->isSeller();
        $starting = false; // La vente n'a pas encore débutée
        $started = false; // La vente est en cours
        $finished = false; // La vente est terminé

        // Une vente qui n'a pas démarrée ou qui est terminée n'est accessible par personne, sauf par le propriétaire de la vente
        if($item !== null) {
            // La vente démarrera bientôt
            if(strtotime($item->date_start) - time() > 0) {
                if($isSeller) {
                    $starting = true;
                    $request->session()->flash('message', 'info|La vente débutera le ' . strftime('%A %d %B %Y', strtotime($item->date_start)) . '.');
                }
            // La vente est terminée
            } elseif(strtotime($item->date_end) - time() < 0) {
                $finished = true;
                $request->session()->flash('message', 'info|La vente s\'est terminée le ' . strftime('%A %d %B %Y', strtotime($item->date_end)) . '.');
            // La vente est en cours
            } else {
                $started = true;
            }
        }

        // Voir le tableau des accès selon les différents critères dans le cahier des charges
        if(!$isSeller && $starting) {
            $request->session()->flash('message', 'danger|Cette vente n\'existe pas.');
            return redirect(route('items'));
        }

        $item->form_id = 'form_' . $item->id;
        $item->userIsSeller = $isSeller;
        $item->lastBidPrice = $item->getPrice();
        $item->userBidsCount = $item->getUserBidsCount();
        $item->userCantBid = Auth::Check() && $item->userBidsCount >= MAX_BID_PER_SALE;

        return view('item')
            ->with(compact('item', 'started', 'starting', 'finished'));
    }

    /**
     * Prépare l'insertion d'un item dans la BDD
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request) {
        $item_id = null;

        $this->validate($request, [
            'name' => 'required|max:200',
            'category' => 'required|in:' . implode(',', Categories::getSlugs()),
            'photo' => 'required|image',
            'price' => 'required|numeric|min:0',
            'date_start' => 'required|date|after:' . date('Y-m-d'),
            'date_end' => 'required|date|after:date_start',
            'description' => 'required',
        ]);

        // S'il y a eu un problème quelconque pendant la création de l'Item
        if(!($item_id = $this->create($request))) {
            $request->session()->flash('message', 'danger|Une erreur s\'est produite');
            return redirect('sell::index');
        }

        // Tout s'est bien passé, on redirige ensuite sur la page de l'annonce
        $request->session()->flash('message', 'success|Votre annonce à bien été mise en ligne !');
        return redirect(route('item', ['id' => $item_id]));
    }

    /**
     * Insère un nouvel item dans la BDD
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function create(Request $request) {
        $datas = $request->all();
        $manager = new ImageManager(['driver' => 'imagick']);

        // Création de l'Item
        $item = new Items();
        $item->user_id = Auth::user()->id;
        $item->category_id = Categories::where('slug', $datas['category'])->value('id');
        $item->name = trim($datas['name']);
        $item->description = trim($datas['description']);
        $item->price = $datas['price'];
        $item->date_start = $datas['date_start'];
        $item->date_end = $datas['date_end'];

        // Récupération de la photo envoyée par l'utilisateur
        $file = $request->file('photo');
        $filename = str_limit(str_replace(['/', '\\'], '', $file->getClientOriginalName()), 30) . str_random();
        $filename_thumb = $filename . '_thumb';

        // Sauvegarde de la photo
        $file->move(public_path('upload'), $filename . '.' . $file->getClientOriginalExtension());

        // On sauvegarde un lien relatif pour le lien de la photo, pour éviter un bordel monstre lors d'un
        // changement de nom de domaine.
        $item->photo = 'upload/' . $filename . '.jpg';

        // Là on peut utiliser le chemin absolu de l'image (/home/..../image.jpg)
        $filename = public_path('upload') . '/' . $filename;
        $filename_thumb = public_path('upload') . '/' . $filename_thumb;

        // Active l'entrelacement sur la photo envoyée par l'utilisateur et réduit la qualité
        $manager->make($filename . '.' . $file->getClientOriginalExtension())
                ->interlace(true)
                ->save($filename . '.jpg', 80);

        // Si par exemple l'utilisateur a envoyé un .png (ou un .JpG), alors on supprime l'image,
        // puisqu'on a déjà sauvegardé une version en .jpg
        if($file->getClientOriginalExtension() !== 'jpg') {
            unlink($filename . '.' . $file->getClientOriginalExtension());
        }

        // Création de la miniature, de taille 300px * ratio px.
        $manager->make($filename . '.jpg')
                ->resize(300, null, function($constraint) {
                    $constraint->aspectRatio();
                })
                ->interlace(true)
                ->save($filename_thumb . '.jpg');

        // Si on a bien réussi à sauvegarder l'Item, on renvoie l'id, pour une redirection sur la page de l'Item
        if($item->save()) {
            return $item->id;
        }

        // Sinon il y a eu un problème. :< :< :<
        return false;
    }
}
