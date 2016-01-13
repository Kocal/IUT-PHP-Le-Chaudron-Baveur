@extends('layouts.default')

@section('title', 'Accueil')

@section('content')
    <h1 class="text-center">Bienvenue au Chaudron Baveur !</h1>

    <div class="text-justify">
	<p>Bienvenue sur le site du <b>Chaudron Baveur</b>, site de ventes aux enchères <i>fictif</i> créé lors d'un TP en PHP à l'IUT.</p>
	<p>La suite romanesque fantasy <b>Harry Potter</b> narre les aventures d'un apprenti sorcier nommé Harry Potter et de ses amis Ron Weasley et Hermione Granger à l'école de sorcellerie Poudlard, dirigée par Albus Dumbledore. L'intrigue principale de la série met en scène le combat du jeune Harry Potter contre un mage noir réputé invincible, Lord Voldemort, qui a tué autrefois ses parents ; à la tête d'un clan de mages noirs, les Mangemorts, Voldemort cherche depuis des décennies à prendre le pouvoir sur le monde des sorciers.</p>

	<hr>
        <h2 class="text-center">Plus grosse vente</h2>
        <p class="text-center">
            @if($maxSale === null)
                <div class="alert alert-info">Aucune vente n'a été réalisée, il n'y a donc rien à afficher.</div>
            @else
                <b>Nom</b> : <a href="{{ url(route('item', ['id' => $maxSale->id])) }}">{{ $maxSale->name }}</a><br>
                <b>Vendeur</b> : {{ $seller->pseudo }}<br>
                <b>Acheteur</b> : {{ $buyer->pseudo }}<br>
                <b>Prix</b> : {{ $maxSale->price }} €<br>
            @endif
        </p>
    </div>
@endsection
