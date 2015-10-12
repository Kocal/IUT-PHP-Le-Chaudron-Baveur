<?php
use App\Bids;
use Illuminate\Support\Str;
?>
@extends('layouts.default')

@section('title', 'Acheter un produit')

@section('js')
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/masonry.min.js"></script>
    <script>
        (function($) {
            var $container = $('.masonry-container');
            $container.masonry({
                columnWidth: '.item',
                itemSelector: '.item'
            });
        })(jQuery);
    </script>

@append

@section('content')
    <h2 class="text-center page-title">Enchères en cours</h2>
    <div class="row masonry-container" >
        @foreach($items as $item)
            <?php
                $min_price = Bids::getBidPriceOrProductPrice($item->id);
                $min_bid = $min_price + 0.01;
            ?>
            <div class="col-md-4 col-sm-6 item">
                <div class="item--thumbnail thumbnail">
                    <a href="{{route('item', ['id' => $item->id])}}">
                        <img src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}" title="{{ $item->name }}" alt="Photo vente : {{ $item->name }}">
                    </a>
                    <div class="caption">
                        <h4 class="item--name">
                            <a href="{{ route('item', ['id' => $item->id]) }}" title="{{ $item->name }}">{{ Str::words($item->name, 10) }}</a>
                        </h4>
                        <p class="item--description">{!! Str::words(nl2br(htmlspecialchars($item->description)), 30) !!}</p>
                        <hr>
                            <p>Prix de vente : <b>{{ $item->price }} €</b><br>
                            Dernière enchère : <b>{{ $min_price === $item->price ? '∅' : $item->price . '€' }}</b></p>
                        <hr class="item--price-separator-down">
                        {!! BootForm::open()->class('form-inline text-right') !!}
                            <div class="form-group {!! $errors->has('price') ? 'has-error' : '' !!}">
                                <div class="input-group">
                                    <input type="number" name="price" id="price" class="form-control" value="<?= old('price') ?>"
                                    placeholder="ex: {{ $min_bid  }}" step="0.01" min="{{ $min_bid }}">
                                    <div class="input-group-addon">€</div>
                                </div>
                                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                            </div>
                        {!! BootForm::submit('Enchérir')->class('btn btn-primary') !!}
                        {!! BootForm::close()  !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center">
        {!! $items->render() !!}
    </div>
@endsection


        <!--
                <div class="grid-item item">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}"
                                 class="item--thumbnail" alt="">
                        </div>
                        <div class="col-md-9">
                            <div class="item--title">{{ trim($item->name) }}</div>
                            <div class="item--description">{{ Str::words(trim($item->description, 4)) }}<br><br>
                                <b>Prix de vente: </b> {{ $item->price }}
        </div>
    </div>
</div>
<div class="item--footer">
    <td>
        {!! BootForm::open('bid')->class('form-inline') !!}

        <div class="form-group">
            <div class="input-group">
                <input type="number" name="price" id="price" class="form-control input-sm" value="{{ old('price') or $min_price }} ?>"
                                           placeholder="ex: {{ $min_price + 0.01 }}" step="0.01" min="{{ $min_price + 0.01 }}">
                                    <div class="input-group-addon">€</div>
                                </div>
                            </div>

                        {!! BootForm::submit('Enchérir')->class('btn btn-primary btn-sm') !!}
{!! BootForm::close() !!}
        </div>
    </div>
{{--</div>--}}
            -->
