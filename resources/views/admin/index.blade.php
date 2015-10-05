@extends('layouts.default')

@section('title', 'Administration')

@section('content')
    <section class="text-center">
        <h1>Administration</h1>
        <h2>Voulez-vous purger les vieilles annonces ?</h2>

        {!! BootForm::open()->method('post')->action(route('admin::purge')) !!}
        {!! BootForm::submit('OUI JE LE VEUX', 'btn btn-danger big-red-button')->onclick('return confirm(\'EST-TU SÛR ????\')') !!}
        {!! BootForm::close() !!}
    </section>
@endsection
