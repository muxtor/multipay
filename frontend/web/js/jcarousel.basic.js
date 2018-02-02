(function ($) {
    $(function () {
        $('.b-slider').jcarousel({
            wrap:'circular'
        });
//        $('.b-slider').jcarouselAutoscroll({
//              interval: 1000,
////              autostart: true
//          });
        $('.b-slider-pagination')
                .on('jcarouselpagination:active', 'a', function () {
                    $(this).addClass('active');
                })
                .on('jcarouselpagination:inactive', 'a', function () {
                    $(this).removeClass('active');
                })
                .jcarouselPagination();


        $('.b-logos-corousel').jcarousel({
            wrap: 'circular'
        });
        $('.control-prev')
                .on('jcarouselcontrol:active', function () {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function () {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '-=1'
                });
        $('.control-next')
                .on('jcarouselcontrol:active', function () {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function () {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '+=1'
                });

    });
})(jQuery);
