@extends('layouts.email-html')
@section('content')
<p>
    Bonjour {{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->pseudo }}),<br>
    <br>
    Votre annonce « <a href="{{ route('item', ['id' => $item->id]) }}">{{ $item->name }}</a> » vient d'expirer, et personne n'a surenchéri.
</p>
@endsection
