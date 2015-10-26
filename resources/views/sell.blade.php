@extends('layouts.default')

@section('title', 'Vendre un objet')

@section('content')
    <h1 class="text-center">Vendre un objet</h1>

    {!! BootForm::open()->action(route('sell::add'))->enctype('multipart/form-data') !!}
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::text('Nom', 'name')->defaultValue(old('name'))->placeholder('ex: Cape d\'invisibilité') !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::file('Photo', 'photo') !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::select('Catégorie', 'category')->options($categories)->defaultValue(old('category')) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group {!! $errors->has('price') ? 'has-error' : '' !!}">
                    <label class="control-label" for="price">Prix minimum</label>
                    <div class="input-group">
                        <input type="number" name="price" id="price" class="form-control" value="<?= old('price') ?>"
                               placeholder="ex: 5,00" step="0.01" min="0">
                        <div class="input-group-addon">€</div>
                    </div>
                    {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <?php
                $date_start = date('Y-m-d', time() + 3600 * 24);
                $date_end = date('Y-m-d', time() + 3600 * 24 * 2);
            ?>

            <div class="col-md-4">
                {!! BootForm::date('Date de mise en vente', 'date_start')->min($date_start)->defaultValue($date_start) !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::date('Date de fin de vente', 'date_end')->min($date_end)->defaultValue($date_end) !!}
            </div>
        </div>
    <div class="row">
        <div class="col-md-12">{!! BootForm::textarea('Description', 'description') !!}</div>
    </div>
    <div class="text-center">
        {!! BootForm::submit('Mettre en vente', 'btn btn-primary btn-lg') !!}
        </div>
    {!! BootForm::close() !!}
@endsection
