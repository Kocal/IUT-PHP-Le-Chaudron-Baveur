@extends('layouts.email-text')
@section('content')
Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),

Votre compte « {{ $seller->pseudo }} » vient d'être désactivé sur le site du Chaudron Baveur, car vous n'avez plus d'annonce en ligne.

Vous pouvez réactiver votre compte à l'adresse suivante : {{ route('account::enable', [
            'user_id' => $seller->id,
            'credentials_hash' => $seller->getHashedCredentials(),
            'deleted_at_hash' => $seller->getHashedDeletedAt()
        ]) }}
@endsection
