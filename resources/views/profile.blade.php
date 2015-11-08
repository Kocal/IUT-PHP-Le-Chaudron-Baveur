<?php
setlocale(LC_ALL, 'fr_FR.utf-8');
?>
@extends('layouts.default')

@section('title', 'Mon profil')

@section('js')
    <script>
        ;(function() {
            var formDelete = document.querySelector('form.delete');

            formDelete.addEventListener('submit', function(e) {
                return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Ce sera définitif.');
            }, false);
        })();
    </script>
@endsection

@section('content')
    <section class="text-center">
        <h1>Mon profil</h1>
        <hr>
        <h2>Mes annonces</h2>
        @if($items->count() === 0)
            <p>Vous n'avez pas encore d'annonces en ligne.</p>
        @else
            <table class="table table-responsive table-striped items-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th class="th-end">(Fin|terminé) le</th>
                        <th>Prix de mise en vente</th>
                        <th>Dernière enchère</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            @if($item->photo)
                                <div class="item--thumbnail">
                                    <a href="{{ route('item', ['id' => $item->id]) }}">
                                        <img width="60px" src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}" title="{{ $item->name }}" alt="Photo vente : {{ $item->name }}">
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td><a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
                        <td>{{ $item->category->name }}</td>
                        <td><time>{{ strftime('%A %d %B %Y', strtotime($item->date_end)) }}</time></td>
                        <td>{{ $item->price }} €</td>
                        <td><b>{{ $item->price === $item->getPrice() ? 'x' : $item->getPrice() . ' €' }}</b></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <hr>

        <h2>Mes enchères</h2>

        @if($bids->count() === 0)
            <p>Vous n'avez pas encore enchéri une annonce.</p>
        @else
            <table class="table table-responsive table-striped items-table">
                <thead>
                    <tr>
                        <th>Miniature</th>
                        <th>Nom</th>
                        <th>Montant</th>
                        <th>Faite le</th>
                        <th>(Fin|Terminé) le</th>

                    </tr>
                </thead>
                <tbody>
                @foreach($bids as $bid)
                    <tr>
                        <td>
                            @if($bid->items->photo)
                                <div class="item--thumbnail">
                                    <a href="{{ route('item', ['id' => $bid->items->id]) }}">
                                        <img width="60px" src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $bid->items->photo)) }}" title="{{ $bid->items->name }}" alt="Photo vente : {{ $bid->items->name }}">
                                    </a>
                                </div>
                            @else
                                x
                            @endif
                        </td>
                        <td><a href="{{ route('item', ['id' => $bid->items->id]) }}">{{ $bid->items->name }}</a></td>
                        <td>{{ $bid->price }} €</td>
                        <td><time>{{ strftime('%a %d %b %Y à %H:%M:%S', strtotime($bid->created_at)) }}</time></td>
                        <td>{{ strftime('%a %d %b %Y', strtotime($bid->items->date_end)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <hr>

        <h2>Supprimer mon compte</h2>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <p class="alert alert-danger">
                    <b>Attention !</b> Supprimer votre compte sur Le Chaudron Baveur entrainera la suppression de vos informations personnelles, ainsi que la relation avec vos ventes, et vos enchères.
                </p>
            </div>
            <div class="col-md-3"></div>
        </div>
        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-2">
                {!! BootForm::open()
                    ->action(route('account::delete', [
                        'credentials_hash' => $credentials_hash
                    ]))
                    ->class('delete')
                !!}
                {!! BootForm::password('Mot de passe', 'password') !!}
                {!! BootForm::submit('Supprimer mon compte')->class('btn btn-danger') !!}
                {!! BootForm::close() !!}
            </div>
            <div class="col-md-5"></div>
        </div>
    </section>
@endsection
