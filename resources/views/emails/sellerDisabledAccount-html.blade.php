@extends('layouts.email-text')
@section('content')
<p>
    Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),<br>
    <br>
    Votre compte <b>{{ $seller->pseudo }}</b> vient d'être désactivé sur le site du Chaudron Baveur, car vous n'avez plus d'annonce en ligne.<br>
    <br>
    Vous pouvez réactiver votre compte en cliquant <a href="{{ route('enable_account', [
            'user_id' => $seller->id,
            'credentials_hash' => $seller->getHashedCredentials(),
            'deleted_at_hash' => $seller->getHashedDeletedAt()
        ]) }}">ici</a>.
</p>
@endsection
