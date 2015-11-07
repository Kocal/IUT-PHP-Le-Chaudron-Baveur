@extends('layouts.email-text')
@section('content')
    <p>
        Bonjour {{ $buyer->first_name }} {{ $buyer->last_name }} ({{ $buyer->pseudo }}),<br>
        <br>
        Votre compte <b>{{ $buyer->pseudo }}</b> vient d'être désactivé sur le site du Chaudron Baveur, car toutes vos enchères sont terminées.<br>
        <br>
        Vous pouvez réactiver votre compte en cliquant <a href="{{ route('account::enable', [
            'user_id' => $buyer->id,
            'credentials_hash' => $buyer->getHashedCredentials(),
            'deleted_at_hash' => $buyer->getHashedDeletedAt()
        ]) }}">ici</a>.
    </p>
@endsection
