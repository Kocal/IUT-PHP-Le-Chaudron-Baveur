@extends('layouts.email-html')
@section('content')
<p>
    Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),<br>
    <br>
    Votre annonce « <a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a> » vient d'être vendue
    à <b>{{ $buyer->pseudo }}</b> pour une valeur de <b>{{ $bid->price }} €</b> !<br>
    <br>
    Voici les coordonnées de l'acheteur :<br>
    <b>Nom :</b> {{ $buyer->first_name }}<br>
    <b>Prénom :</b> {{ $buyer->last_name }}<br>
    <b>Numéro de téléphone :</b> {{ $buyer->phone }}<br>
    <b>Adresse e-mail :</b> {{ $buyer->email }}<br>
    <b>Adresse postale :</b> {!! nl2br(htmlspecialchars(trim($buyer->address))) !!}
</p>
@endsection
