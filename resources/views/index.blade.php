@extends('layouts.default')

@section('title', 'Accueil')

@section('content')
    <h1 class="text-center">Bienvenue au Chaudron Baveur !</h1>

    <div class="text-justify">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate eaque eius in incidunt labore, maxime minima
            natus possimus repellat similique, suscipit voluptatem. Delectus deleniti dignissimos dolor laboriosam nemo
            quaerat, sed similique veniam? Adipisci alias blanditiis culpa debitis delectus dolore eius eligendi, esse fugit
            impedit incidunt inventore ipsa, iste nam nostrum officia quaerat quas recusandae saepe soluta tempora tempore
            voluptates voluptatum. Accusamus ad at commodi dicta earum error esse illo inventore ipsa iusto non quos, sint
            tempora velit voluptatibus! Aliquam aperiam consequuntur dolorum enim eveniet ex expedita, molestiae neque nulla
            numquam optio, repellat repudiandae sint soluta suscipit temporibus voluptatibus. Accusantium, aspernatur.</p>
        <p>Aperiam architecto commodi cupiditate dolorem doloribus exercitationem id laudantium magnam modi mollitia
            necessitatibus officiis perspiciatis, praesentium sed sit veritatis vero vitae voluptate voluptatem voluptates.
            Blanditiis consectetur ea eaque eius fugit maiores, minus officia, omnis placeat quam quidem, voluptas? Dolor
            earum et eum fugit placeat, sapiente sed voluptates! Aperiam autem consectetur cum delectus deleniti deserunt
            distinctio dolore doloremque doloribus, eius eos facilis inventore, ipsum libero maiores minima non obcaecati
            perferendis placeat quaerat quas repellat repellendus reprehenderit similique tempora tenetur unde voluptatem!
            Earum eos necessitatibus nulla pariatur ratione rerum saepe tenetur voluptate? Aliquam amet culpa dolores,
            expedita odio quidem quos repellendus ullam.</p>
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
