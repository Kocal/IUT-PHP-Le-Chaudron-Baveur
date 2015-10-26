<?php
use App\Bids;
use App\User;
use App\Categories;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

setlocale(LC_ALL, 'fr_FR.UTF-8');
?>
@extends('layouts.default')

@section('title', 'Acheter un produit')

@section('js')
<script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('js/countdown.min.js') }}"></script>
<script>
    (function($) {
        var currentTime = +new Date({{ time() * 1000 }});

        countdown.resetLabels();
        countdown.setLabels(
            ' milliseconde| seconde| minute| heure| jour| semaine| mois| année| décennie| siècle| millénaire',
            ' millisecondes| secondes| minutes| heures| jours| semaines| mois| années| décennies| siècles| millénaires',
            ' et ', ', ', 'maintenant');

        $('time.countdown').each(function(k, obj) {
            var $timer = $(obj);
            var time = $timer.data('timestamp') * 1000 + currentTime;

            countdown(time, function(ts) {
                $timer.html(ts.toHTML());
            }, countdown.DAYS | countdown.HOURS | countdown.MINUTES | countdown.SECONDS);
        });
    })(jQuery);
</script>
@append

@section('content')
    <h2 class="text-center page-title">Enchères en cours</h2>
    <hr>
    <div class="row">
        {!! BootForm::open()->action(route('redirect_to'))->method('get')->name('form_sort')->class('form-inline text-right') !!}
        {!! BootForm::select('Trier par :&nbsp;', 'url')->options($sortOptionsDefinitions)->select(Request::url()) !!}
        {!! BootForm::submit('Trier') !!}
        {!! BootForm::close() !!}
    </div>
    <hr>
    <div class="items-row">
        <?php foreach($items as $item):
            $min_price = Bids::getLastBidPriceOrProductPrice($item->id);
            $min_bid = $min_price + 0.01;
            $date_end = strtotime($item->date_end);
            $form_id = 'form_' . $item->id;
        ?>
            <div class="item">
                <div class="well item--well">
                    <div class="thumbnail item--thumbnail">
                        <a href="{{ route('item', ['id' => $item->id]) }}">
                            <img src="{{ asset(preg_replace('/\.jpg$/', '_thumb.jpg', $item->photo)) }}" title="{{ $item->name }}" alt="Photo vente : {{ $item->name }}">
                        </a>
                    </div>
                    <div class="caption">
                        <h3 class="item--name">
                            <a href="{{ route('item', ['id' => $item->id]) }}" title="{{ $item->name }}">{{ Str::words($item->name, 10) }}</a>
                        <small>({{ $item->category->name }})</small></h3>
                        <p>
                            Se termine dans <b><time class="countdown" data-timestamp="{{ $item->getDateDiff() }}">
                                {{ strftime('%A %d %B %Y', $date_end) }}</time></b><br>
                            Prix d'entrée : <b>{{ $item->price }}€</b><br>
                            Dernière enchère : <b>{{ $min_price === $item->price ? '∅' : $min_price. '€' }}</b>
                        </p>

                        <?php if(!Auth::check() || (Auth::check() && Auth::user()->id !== $item->user_id)): ?>
                            {!! BootForm::open()->action(route('bid', ['id' => $item->id]))->name('form-bid-' . $item->id)->class('form-inline text-right') !!}

                            <input type="hidden" name="_form_id" value="{{ $form_id }}">

                            <div class="form-group {!! $errors->$form_id->has('price') ? 'has-error' : '' !!}">
                                <div class="input-group">
                                    <input type="number" name="price" id="price" class="form-control" value="<?= old('price') ?>"
                                    placeholder="ex: {{ $min_bid  }}" step="0.01" <!--min="{{ $min_bid }}">
                                    <div class="input-group-addon">€</div>
                                </div>

                                {!! $errors->$form_id->first('price', '<p class="help-block">:message</p>') !!}
                            </div>

                            {!! BootForm::submit('Enchérir')->class('btn btn-primary') !!}
                            {!! BootForm::close()  !!}
                        <?php else: ?>
                            <b>Impossible d'enchérir votre enchère !</b>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center">
        {!! $items->render() !!}
    </div>
@endsection
