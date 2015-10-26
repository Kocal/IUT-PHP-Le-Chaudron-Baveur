<?php
use App\Bids;
use App\User;
use App\Categories;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

setlocale(LC_ALL, 'fr_FR.UTF-8');
?>
@extends('layouts.default')

@section('title', 'Acheter un produit')

@section('js')
    <script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <script>
        var currentTime = +new Date({{ time() * 1000 }});
    </script>
    <script src="{{ asset('js/countdown.js') }}"></script>
@append

@section('content')
    <h2 class="text-center page-title">Enchères en cours</h2>
    <hr>
    <div class="row">
        {!! BootForm::open()->action(route('redirect_to'))->method('get')->name('form_sort')->class('form-inline text-right') !!}
        {!! BootForm::select('Trier par :&nbsp;', 'url')->options($sortOptionsDefinitions)->select(Request::url()) !!}
        {!! BootForm::submit('Trier') !!}
        {!! BootForm::close() !!}
    </div>
    <hr>

    {{--
        # Bricolage pour afficher le message d'erreur après avoir proposé un prix trop bas.

        En fait, Session::get('errorBag') peut contenir "form_$n" où $n = n..*, qui servira ensuite pour colorer en
        rouge le formulaire où il y a eu une erreur.

        Souhaitant afficher le message d'erreur (spécifique au formulaire où il y a eu une erreur) à cet endroit,
        soit je faisais une boucle afin de tester les combinaisons possible entre $items[0]->id et $items[count($items) - 1]->id,
        soit je sauvegardais le "bag d'erreur" en session flash, et ensuite bricolais un peu avec la syntaxe de PHP pour récupérer le message d'erreur
    --}}
    {!! $errors->{Session::get('errorBag')}->first('price', '<div class="alert alert-danger">:message</div>') !!}

    <table class="table table-responsive table-striped items-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Vendeur</th>
                <th class="th-end">Fin le</th>
                <th>Dernière enchère</th>
                <th width="250px">Enchérir {{ (Auth::check() ? '(' . MAX_BID_PER_SALE . ' enchères max)' : '') }}</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):
            // Prix minimum de la vente
            $price = $item->getPrice();
            // Détermine si l'utilisateur connecté est le vendeur de l'annonce
            $isSeller = $item->isSeller();
            // Retourne le nombre d'enchère d'un utilisateur sur cette annonce
            $bidCount = $item->getBidCountByUserId(Auth::check() ? Auth::user()->id : null);
            // Si l'utilisateur a dépassé le nombre max de propositons d'enchères
            $cantBid = Auth::check() && $bidCount == MAX_BID_PER_SALE;

            $min_bid = $price + 1;
            $date_end = strtotime($item->date_end);
            $form_id = 'form_' . $item->id;
        ?>
            <tr>
                <td><a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
                <td>{{ $item->category->name }}</td>
                <td class="text-center">{{ $item->user->email }}{{ $isSeller ? ' (vous)' : '' }}</td>
                <td class="text-right"><time class="countdown text-right" data-timestamp="{{ $item->getDateDiff() }}">{{ strftime('%A %d %B %Y', $date_end) }}</time></td>
                <td class="text-right"><b>{{ $price }} €</b></td>
                <td class="text-center">
                    @if($isSeller)
                        <b>Vous ne pouvez pas renchérir votre propre enchère !</b>
                    @elseif($cantBid)
                        <b>Vous avez dépassé le nombre maximum d'essais pour cette enchère !</b>
                    @else
                        {!! BootForm::open()->action(route('bid', ['id' => $item->id]))->class('form-inline text-right form-small') !!}
                            <input type="hidden" name="_form_id" value="{{ $form_id }}">

                            <div class="form-group {!! $errors->$form_id->has('price')  ? 'has-error' : '' !!}">
                                <div class="input-group">
                                    <input type="number"  name="price" class="form-control input-sm" value="{{ $errors->$form_id->has('price') ? old('price') : '' }}"
                                           placeholder="{{ sprintf('%-.2f', $min_bid) }}" step="0.01" min="{{ str_replace(',', '.', $min_bid) }}">
                                    <div class="input-group-addon">&euro;</div>
                                    <span class="input-group-btn">
                                        {!! BootForm::submit('Enchérir' . ' (' . $bidCount . '/' . MAX_BID_PER_SALE  . ')')
                                            ->class('btn btn-primary btn-sm') !!}
                                    </span>
                                </div>
                            </div>
                        {!! BootForm::close() !!}
                    @endif
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        {!! $items->render() !!}
    </div>
@endsection
