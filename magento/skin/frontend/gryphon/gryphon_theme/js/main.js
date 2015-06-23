// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

(function($){
    $(document).ready(function(){

        // $('a.login').fancybox({
        //     padding: 50,
        //     width: 300,
        //     height: 300,
        //     autoDimensions: false,
        //     closeBtn : false
        // });

        $('.account-details').find('.save-btn').on('click', function(e){
            $('.account-details-content').slideToggle('slow');
            $('.account-details-saved-content').slideToggle('slow');
            $('.edit').slideToggle('slow');
        });

        $('.account-details-saved').find('.edit').on('click', function(e){
            $('.account-details-saved-content').slideToggle('slow');
            $('.account-details-content').slideToggle('slow');
            $('.edit').slideToggle('slow');
        });

        $('.scroll-to-content').on('click', function(e){
            e.preventDefault();
            var currentId = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(currentId).offset().top - 100
            }, 800);
        });

        /*$('.cart-breadcrumb a').click(function (e) {
          e.preventDefault()
          $('.cart-breadcrumb a').removeClass('active');
          $(this).addClass('active');
        });*/

        $('.load-more').on('click', function(e){
            e.preventDefault();

            var $loadmore = $(this);
            var link = $loadmore.attr('href');
            $(link).slideToggle('slow', function(){
                if($(link).css('display') !== 'none')
                    $loadmore.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                else
                    $loadmore.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
        });

        $('.expanded').hide();
        $('.view-detail').on('click', function(e){
            e.preventDefault();
            // $(this).find('span').text('Hide Detail for Grand Total');
            $(this).parent().parent().parent().find('.expanded').slideToggle("slow").addClass('open');
        });

        // rating
        $('.stars').raty({
            path : "js/plugins/raty/images/"
        });

        // mobile menu
        $('.mobile-menu-btn').on('click', function(e){
            $('.mobile-menu').slideToggle( "slow" );
        });

        $('.mobile-menu li > a').on('click', function(e){
            if($(this).text()=="TEAWARE") {
                e.preventDefault();
            }

            // $('.open').slideToggle("slow").removeClass('open');
            $(this).parent().find('.sub-menu').slideToggle("slow").addClass('open');
        });

        // home page carousel
        $('.slick').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1099,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 960,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 760,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                }
            ]
        });

        // product page carousel
        $('.similar-product-carousel').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1099,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                }
            ]
        });

        // clearing filters
        $('.clear-all').on('click', function(e){
            $('input[type="checkbox"]').prop('checked', false);
        });

        $('#new').click(function (e){
            e.preventDefault();
           $('link[href="css/style.css"]').attr('href','css/style2.css');
        });
        $('#original').click(function (e){
            e.preventDefault();
           $('link[href="css/style2.css"]').attr('href','css/style.css');
        });

        function setGetParameter(paramName, paramValue)
        {
            var url = window.location.href;
            if (url.indexOf(paramName + "=") >= 0)
            {
                var prefix = url.substring(0, url.indexOf(paramName));
                var suffix = url.substring(url.indexOf(paramName));
                suffix = suffix.substring(suffix.indexOf("=") + 1);
                suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
                url = prefix + paramName + "=" + paramValue + suffix;
            }
            else
            {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
            }
            window.location.href = url;
        }


        /////////////////////////////////////
        // product page filters + loadmore
        /////////////////////////////////////

        $('#sort-by-price').on('click', function(e){
            e.preventDefault();

            setGetParameter('order', 'price');
        });

        $('#sort-by-name').on('click', function(e){
            e.preventDefault();

            setGetParameter('order', 'name');
        });

        $('#sort-by-new').on('click', function(e){
            e.preventDefault();

            setGetParameter('dir', 'desc');
        });

        function sendLoadMoreProductsRequest(url) {
            $.get(url, function(response) {

                var $result = $(response).find('.products');

                $('.products').append($result.html());

                $('.load-more-products').text('load more');
            });
        }

        //Assigning click event to the button which triggers the "next" link
        /*$('.load-more-products').on('click', function(e) {
            e.preventDefault();

            $(this).text('loading...');

            if($('.i-next').length){
                var nextPageUrl = $('.next.i-next').attr('href');
                sendLoadMoreProductsRequest(nextPageUrl);
            }
            else{
                $(e.currentTarget).hide();
            }
        });*/

        $('input[name="product_type"]').on('change', function(e) {
            if ($(this).is(':checked')) {
                setGetParameter('product_type', $(this).val());
            }else {
                console.log('unchecked')
            }
        });

    });
})(jQuery);