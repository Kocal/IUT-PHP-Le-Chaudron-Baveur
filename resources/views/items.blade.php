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
                $min_price = Bids::getLastBidPriceOrProductPrice($item->id);
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
                        <hr>
                        <p>
                            Prix d'entrée : <b>{{ $item->price }}€</b><br>
                            Dernière enchère : <b>{{ $min_price === $item->price ? '∅' : $min_price. '€' }}</b>
                        </p>
                        <hr class="item--price-separator-down">

                        <?php $form_id = 'form_' . $item->id; ?>

                        {!! BootForm::open()
                                ->action(route('bid', ['id' => $item->id]))
                                ->name('form-bid-' . $item->id)
                                ->class('form-inline text-right') !!}

                            <input type="hidden" name="_form_id" value="{{ $form_id }}">

                            <div class="form-group {!! $errors->$form_id->has('price') ? 'has-error' : '' !!}">
                                <div class="input-group">
                                    <input type="number" name="price" id="price" class="form-control" value="<?= old('price') ?>"
                                    placeholder="ex: {{ $min_bid  }}" step="0.01" <!--min="{{ $min_bid }}-->">
                                    <div class="input-group-addon">€</div>
                                </div>

                                {!! $errors->$form_id->first('price', '<p class="help-block">:message</p>') !!}
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
