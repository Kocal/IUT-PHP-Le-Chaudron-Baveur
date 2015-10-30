@extends('layouts.email-text')
@section('content')
<p>
    Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),<br>
    <br>
    Votre compte <b>{{ $seller->pseudo }}</b> vient d'être supprimé sur le site du Chaudron Baveur, car vous n'avez plus d'annonce en ligne.
</p>
@endsection
