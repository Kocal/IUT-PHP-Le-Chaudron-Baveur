<?php
use App\User;
use App\Bids;
use App\Categories;
?>
@extends('layouts.default')

@section('title', e($item->name))

@section('content')
    <?php
    $min_price = Bids::getLastBidPriceOrProductPrice($item->id);
    $min_bid = $min_price + 0.01;
    ?>
    <h2 class="item--name">{{ trim($item->name) }}</h2>
    <div class="row">
        <div class="col-md-3 thumbnail">
            <a href="{{ asset($item->photo) }}" target="_blank">
                <img src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}"/>
            </a>
        </div>
        <div class="col-md-9">
            <div class="alert alert-info">Vendu par <b>{{ User::getEmailById($item->user_id) }}</b>,
                dans la catégorie <b>{{ Categories::getNameById($item->category_id) }}</b>.<br>
                Date de début de vente : <b>{{ $item->date_start }}</b>, date de fin de vente : <b>{{ $item->date_end }}</b></div>
            <p>{!! nl2br(htmlspecialchars(trim($item->description))) !!}</p>
            <hr>
            <div class="text-right">
                <p>
                    Prix d'entrée : <b>{{ $item->price }} €</b><br>
                    Dernière enchère : <b>{{ $min_price === $item->price ? '∅' : $min_price. '€' }}</b>
                </p>
                    {!! BootForm::open()
                        ->action(route('bid', ['id' => $item->id]))
                        ->class('form-inline text-right') !!}


                        <div class="form-group  {!! $errors->has('price') ? 'has-error' : '' !!}" style="vertical-align: top">
                            <div class="input-group">
                                <input type="number" name="price" id="price" class="form-control" value="<?= old('price') ?>"
                                       placeholder="ex: {{ $min_bid  }}" step="0.01" min="{{ $min_bid }}">
                                <div class="input-group-addon">€</div>
                            </div>
                            {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                        </div>

                    {!! BootForm::submit('Enchérir')->class('btn btn-primary') !!}
                    {!! BootForm::close()  !!}
            </div>
        </div>
    </div>

@endsection
