<?php
use App\User;
use App\Categories;
?>
@extends('layouts.default')

@section('title', 'Acheter un produit')

@section('content')
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Vendeur</th>
                    <th>Prix minimum</th>
                    <th>Enchérir</th>
                </tr>
            </thead>
        @foreach ($items as $item)
            <?php
                $user = User::get()->where('id', $item->user_id)->first();
                $category = Categories::get()->where('id', $item->category_id)->first();
            ?>
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</td>
                <td>{{ $item->minimum_price }} &euro;</td>
                <td>
                    {!! BootForm::open()->class('form-inline') !!}

                    <div class="form-group">
                        <div class="input-group">
                            <input type="number" name="price" id="price" class="form-control input-sm" value="<?= old('price') ?>"
                                   placeholder="ex: 42,42" step="0.01" min="0">
                            <div class="input-group-addon">€</div>
                        </div>
                    </div>

                    {!! BootForm::submit('Enchérir')->class('btn btn-primary btn-sm') !!}
                    {!! BootForm::close() !!}
                </td>
            </tr>
        @endforeach
        </table>
    </div>

    <div class="text-center">{!! $items->render() !!}</div>
@endsection
