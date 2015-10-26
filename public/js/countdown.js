var currentTime = currentTime || +new Date();

(function($) {
    $('.th-end').text('Temps restant');

    $('time.countdown').each(function(k, clock) {
        var $clock = $(clock);
        var timer = $clock.data('timestamp') * 1000 + currentTime;

        $clock.countdown(timer)
            .on('update.countdown', function(event) {
                var format = '%H:%M:%S';

                if(event.offset.days > 0) {
                    format = '%-D %!D:jour,jours; ' + format;
                }
    
                $clock.html(event.strftime(format));
            })
            .on('finish.countdown', function(event) {
                $clock.parents('tr').addClass('item_bid-ended');
                $click.html('La vente est terminée !');      
            });
    });
})(jQuery);
