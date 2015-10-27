@extends('layouts.default')

@section('title', 'Administration')

@section('content')
    <section class="text-center">
        <h1>Administration</h1>
        <h2>Voulez-vous épurer la base de données ?</h2>

        {!! BootForm::open()->method('post')->action(route('admin::refine')) !!}
        {!! BootForm::submit('Oui', 'btn btn-danger big-red-button')->onclick('return confirm(\'Êtes-vous vraiment sûr\')') !!}
        {!! BootForm::close() !!}
    </section>
@endsection
