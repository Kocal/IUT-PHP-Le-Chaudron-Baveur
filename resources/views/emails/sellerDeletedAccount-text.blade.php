@extends('layouts.email-text')
@section('content')
Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),

Votre compte « {{ $seller->pseudo }} » vient d'être supprimé sur le site du Chaudron Baveur, car vous n'avez plus d'annonce en ligne.
@endsection
