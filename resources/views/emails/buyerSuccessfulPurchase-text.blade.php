@extends('layouts.email-text')
@section('content')
Bonjour {{ $buyer->first_name }} {{ $buyer->last_name }} ({{ $buyer->pseudo }}),

Vous venez de remporter l'enchère « {{ $item->name }} » ({{ route('item', ['id' => $item->id]) }}) mise en vente par {{ $seller->pseudo }}, pour une valeur de {{ $bid->price }} € !

Voici les coordonnées du vendeur :
Nom : {{ $seller->first_name }}
Prénom : {{ $seller->last_name }}
Numéro de téléphone : {{ $seller->phone }}
Adresse e-mail : {{ $seller->email }}
Adresse postale : {{  $seller->address }}
@endsection
