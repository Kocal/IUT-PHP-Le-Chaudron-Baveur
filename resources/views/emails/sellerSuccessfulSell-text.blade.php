@extends('layouts.email-text')
@section('content')
Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),

Votre annonce « {{ $item->name }} » ({{ route('item', ['id' => $item->id]) }}) vient d'être vendue à {{ $buyer->pseudo }} pour une valeur de {{ $bid->price }} € !

Voici les coordonnées de l'acheteur :
Nom : {{ $buyer->first_name }}
Prénom : {{ $buyer->last_name }}
Numéro de téléphone : {{ $buyer->phone }}
Adresse e-mail : {{ $buyer->email }}
Adresse postale : {{  $buyer->address }}
@endsection
