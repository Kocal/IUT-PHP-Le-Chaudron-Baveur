<?php
setlocale(LC_ALL, 'fr_FR.UTF-8');
?>

@extends('layouts.default')

@section('title', e($item->name))

@section('js')
<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
<script>
    var currentTime = +new Date({{ time() * 1000 }});
</script>
<script src="{{ asset('js/countdown.js') }}"></script>
@endsection

@section('content')
    <?php
    $price = $item->getPrice();
    $isSeller = $item->isSeller();
    $bidCount = $item->getBidCountByUserId(Auth::check() ? Auth::user()->id : null);
    $cantBid = Auth::check() && $bidCount == MAX_BID_PER_SALE;

    $min_bid = $price + 1;
    $form_id = 'form_' . $item->id;
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="text-center">
                @if(empty($item->photo))
                    <p>Il n'y a pas de miniature pour cette annonce.</p>
                @else
                    <a href="{{ asset($item->photo) }}" class="thumbnail item--thumbnail" target="_blank">
                        <img src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}"/>
                    </a>
                @endif
            </div>
            <hr>
            <p>
                Vendeur : <b>{{ $item->user->email }}</b><br>
                Catégorie : <b>{{ $item->category->name }}</b>
            </p>

            @if($started)
            <p>
                <span class="th-end">Se termine le</span> : <b><time class="countdown" data-timestamp="{{ $item->getDateDiff() }}">
                {!! strftime('%A %d %B %Y', strtotime($item->date_end)) !!}</time></b>
            </p>
            @endif
            <hr>
            <p>
                Dernière enchère : <b>{{ $price }} &euro;</b>
            </p>

            <p class="text-center">
                @if($isSeller)
                    <b>Vous ne pouvez pas renchérir votre propre enchère !</b>
                @elseif($cantBid)
                    <b>Vous avez dépassé le nombre maximum d'essais pour cette enchère !</b>
                @else
                    {!! BootForm::open()->action(route('bid', ['id' => $item->id]))->class('form-inline') !!}
                        <input type="hidden" name="_form_id" value="{{ $form_id }}">

                        <div class="form-group {!! $errors->$form_id->has('price')  ? 'has-error' : '' !!}">
                            {!! $errors->$form_id->first('price', '<div class="alert alert-danger">:message</div>') !!}
                            <div class="input-group">
                                <input type="number"  name="price" class="form-control" value="{{ $errors->$form_id->has('price') ? old('price') : '' }}"
                                       placeholder="{{ sprintf('%-.2f', $min_bid) }}" step="0.01" min="{{ str_replace(',', '.', $min_bid) }}">
                                <div class="input-group-addon">&euro;</div>
                            </div>

                            <div class="input-group">
                                {!! BootForm::submit('Enchérir' . ' (' . $bidCount . '/' . MAX_BID_PER_SALE  . ')')
                                    ->class('btn btn-primary') !!}
                            </div>
                        </div>
                    {!! BootForm::close() !!}
                @endif
            </p>
        </div>
        <div class="col-md-8">
            <h2 class="text-center item--name">{{ trim($item->name) }}</h2>
            <p>{!! nl2br(htmlspecialchars(trim($item->description))) !!}</p>
        </div>
    </div>
@endsection
