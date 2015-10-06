@extends('layouts.default')

@section('title', 'S\'inscrire')

@section('content')
<div class="row">
    <div class="col-md-7 container-fluid">
        <h2 class="text-center">S'inscrire</h2>
        {!! BootForm::open() !!}
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('Prénom', 'first_name')
                ->placeholder('ex: John')->defaultValue(old('first_name')) !!}
                </div>
                <div class="col-md-6">
                    {!! BootForm::text('Nom', 'last_name')
                ->placeholder('ex: Smith')->defaultValue(old('last_name')) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::email('Adresse e-mail', 'email')
                ->placeholder('ex: john.smith@mail.com')->defaultValue(old('email')) !!}
                </div>
                <div class="col-md-6">
                    {!! BootForm::text('Numéro de téléphone', 'phone')
                ->placeholder('ex: 01 23 45 67 89')->defaultValue(old('phone')) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('Adresse postale', 'address')->defaultValue(old('address'))->rows(4) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::password('Mot de passe', 'password') !!}
                </div>
                <div class="col-md-6">
                    {!! BootForm::password('Mot de passe (confirmation)', 'password_confirmation') !!}
                </div>
            </div>

            <div class="text-center">
                {!! BootForm::submit('S\'inscrire', 'btn btn-primary btn-lg') !!}
            </div>
        {!! BootForm::close() !!}
    </div>
    <div class="col-md-5 text-center">
        <h2>Vous avez déjà un compte ?</h2>
        <p><a href="{{ route('login') }}" class="btn btn-primary" role="button">Se connecter</a></p>
    </div>
</div>
{{-- <div class="clearfix"></div> --}}
@endsection
