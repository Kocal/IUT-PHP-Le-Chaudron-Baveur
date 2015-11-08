<?php
setlocale(LC_ALL, 'fr_FR.UTF-8');

use App\User;
use App\Items;
use App\Bids;
use Illuminate\Support\Str;
?>

@extends('layouts.default')

@section('title', 'Administration')

@section('content')
        <h1 class="text-center">Administration</h1>
        <hr>
        @if(session('logs') !== null)
            <div class="alert alert-info">
            @if(empty(session('logs')))
                &laquo; <i>Rien ne se passe...</i> &raquo
            @else
                @foreach(session('logs') as $item_id => $logs)
                    <ul>
                        <li>
                            <span>Enchère n° {{ $item_id }}</span>
                            <ul>
                                @foreach($logs as $log)
                                    @if(!empty($log))
                                        <li>{{ $log }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                @endforeach
            @endif
            </div>
            <hr>
        @endif
        <h2 class="text-center">Ventes terminées</h2>

        @if(count($items) === 0)
            <p class="text-center">Aucune vente ne s'est terminée aujourd'hui.</p>
        @else
            <table class="text-center table table-responsive table-striped items-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Vendeur</th>
                    <th>Acheteur</th>
                    <th>Prix de départ</th>
                    <th>Dernier prix proposé</th>
                    <th>Vente terminée le</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td><a href="{{ route('item', ['id' => $item->id]) }}">{{ Str::words($item->name, 3) }}</a></td>
                        <td>{{ $item->user->pseudo }}</td>
                        <td>{{ $item->gotBid ? $item->lastBid->user->pseudo : 'aucun'  }}</td>
                        <td>{{ $item->price }} €</td>
                        <td>{{ $item->gotBid ? sprintf('%-.2f', $item->lastBid->price) . ' €' : 'Ø' }}</td>
                        <td>{{ strftime('%A %d %B %Y', strtotime($item->date_end)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <hr>

        <h2 class="text-center">Voulez-vous épurer la base de données ?</h2>
        {!! BootForm::open()
            ->method('post')
            ->action(route('admin::refine'))
            ->class('text-center') !!}
        {!! BootForm::submit('Oui', 'btn btn-danger big-red-button')->onclick('return confirm(\'Êtes-vous vraiment sûr ?\')') !!}
        {!! BootForm::close() !!}

@endsection
