@extends('layouts.default')

@section('title', 'Se connecter')

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-3">
        <h2 class="text-center">Se connecter</h2>
        {!! BootForm::open() !!}
        {!! BootForm::email('Adresse e-mail', 'email')->placeholder('ex: john.smith@mail.com')->defaultValue(old('email')) !!}
        {!! BootForm::password('Mot de passe', 'password')->placeholder('qsdqsd') !!}
        {!! BootForm::checkbox('Garder ma session ouverte', 'remember') !!}
        <div class="text-center">
        {!! BootForm::submit('Se connecter', 'btn btn-primary btn-lg') !!}
        </div>
        {!! BootForm::close() !!}
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-5 text-center">
        <h2>Vous êtes nouveau sur Le Chaudron Baveur ?</h2>
        <p>
            Inscrivez-vous, c'est simple et rapide !
            <div class="form-group">
            <a href="{{ route('register') }}" class="btn btn-primary" role="button">S'inscrire</a>
        </div>
        </p>
    </div>
</div>
@endsection
