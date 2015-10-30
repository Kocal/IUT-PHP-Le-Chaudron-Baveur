@extends('layouts.email-text')
@section('content')
Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),

Votre annonce « {{ $item->name }} » ({{ route('item', ['id' => $item->id]) }}) vient d'expirer, et personne n'a surenchéri.
@endsection
