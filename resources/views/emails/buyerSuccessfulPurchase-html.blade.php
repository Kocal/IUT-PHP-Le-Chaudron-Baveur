@extends('layouts.email-html')
@section('content')
<p>
    Bonjour {{ $buyer->first_name }} {{ $buyer->last_name }} ({{ $buyer->pseudo }}),<br>
    <br>
    Vous venez de remporter l'enchère « <a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a> »
    mise en vente par <b>{{ $seller->pseudo }}</b>, pour une valeur de <b>{{ $bid->price }} €</b> !<br>
    <br>
    Voici les coordonnées du vendeur :<br>
    <b>Nom :</b> {{ $seller->first_name }}<br>
    <b>Prénom :</b> {{ $seller->last_name }}<br>
    <b>Numéro de téléphone :</b> {{ $seller->phone }}<br>
    <b>Adresse e-mail :</b> {{ $seller->email }}<br>
    <b>Adresse postale :</b> {!! nl2br(htmlspecialchars(trim($seller->address))) !!}
</p>
@endsection
