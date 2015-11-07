@extends('layouts.email-text')
@section('content')
Bonjour {{ $buyer->first_name }} {{ $buyer->last_name }} ({{ $buyer->pseudo }}),

Votre compte « {{ $buyer->pseudo }} » vient d'être désactivé sur le site du Chaudron Baveur, car toutes vos enchères sont terminées.

Vous pouvez réactiver votre compte à l'adresse suivante : {{ route('account::enable', [
            'user_id' => $buyer->id,
            'credentials_hash' => $buyer->getHashedCredentials(),
            'deleted_at_hash' => $buyer->getHashedDeletedAt()
        ]) }}
@endsection
