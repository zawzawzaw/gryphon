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


var initialLoad = true;

(function($){
    $(document).ready(function(){


        setTimeout(function(){
            $('html').animate({scrollTop:0}, 1);
            $('html').addClass('load-complete');
            console.log('back to top')
        }, 100);
        

        // http://stackoverflow.com/questions/11486527/reload-browser-does-not-reset-page-to-top
        /*
        $('html').animate({scrollTop:0}, 1);
        $('body').animate({scrollTop:0}, 1);
        setTimeout(function(){
            $('html').animate({scrollTop:0}, 1);
            $('body').animate({scrollTop:0}, 1);
            console.log('back to top')
        }, 100);
        setTimeout(function(){
            $('html').animate({scrollTop:0}, 1);
            $('body').animate({scrollTop:0}, 1);
            console.log('back to top')
        }, 300);
        setTimeout(function(){
            $('html').animate({scrollTop:0}, 1);
            $('body').animate({scrollTop:0}, 1);
            console.log('back to top')
        }, 500);
        */

        // http://stackoverflow.com/questions/19999388/check-if-user-is-using-ie-with-jquery

        /**
         * detect IE
         * returns version of IE or false, if browser is not Internet Explorer
         */
        function detectIE() {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
            }

            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf('rv:');
                return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
            }

            var edge = ua.indexOf('Edge/');
            if (edge > 0) {
               // IE 12 => return version number
               return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
            }

            // other browser
            return false;
        }

        if (detectIE() != false) {
            $('html').addClass('is-ie');
        }
        


        // from inside-pages.js
        function myScroller()  {
        var scrollPos = $(window).scrollTop();
        
            if( ( scrollPos != 0 ) ) {
                jQuery('#header-wrapper').addClass('shadow');
                $('.scroll-to-content .arrow').hide();
                if(scrolled==false && initialLoad==false) {
                    scrolled = true;               
                }
                    
            }       
            else if( ( scrollPos === 0 ) && (scrolled == true) ) {
                scrolled = false;
                $('#header-wrapper').removeClass('shadow');
                $('.scroll-to-content .arrow').show();
            }
        }

        var initialLoad = true;
        // home page first scroll
        var scrolled = false;
        $(window).on('scroll', function() {
           myScroller();
        });

        myScroller();

        initialLoad = false;  



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

        // $('.account-details-saved').find('.edit').on('click', function(e){
        //     $('.account-details-saved-content').slideToggle('slow');
        //     $('.account-details-content').slideToggle('slow');
        //     $('.edit').slideToggle('slow');
        // });

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
                    $loadmore.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
                else
                    $loadmore.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
            });
        });

        $('.expanded').hide();
        $('.view-detail').on('click', function(e){
            e.preventDefault();
            console.log('view-detail');
            // $(this).find('span').text('Hide Detail for Grand Total');
            $(this).parent().parent().next().find('.expanded').slideToggle("slow").addClass('open');
        });

        try{
            var baseurl = getBaseUrl();
            console.log(baseurl);

            // rating
            $('.stars').raty({
                path : getBaseUrl()+"/skin/frontend/gryphon/gryphon_theme/js/plugins/raty/images/",
                click: function(score, evt){
                    console.log(score);
                    $('#product-review-table').find('#Price_'+score).trigger('click')
                    $('#product-review-table').find('#Value_'+score).trigger('click')
                    $('#product-review-table').find('#Quality_'+score).trigger('click')
                }
            });

        } catch(e){
            console.log('custom error with function getBaseUrl');
        }

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
                        slidesToShow: 2,
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


    
        function sendLoadMoreProductsRequestHOMEPAGE(url) {
            $.get(url, function(response) {

                var $result = $(response).find('#all-posts');
                var $next = $(response).find('.next-page a');

                var new_link = $next.attr('href');

                if(typeof(new_link)!="undefined") {
                    $('.next-page a').attr('href', new_link);
                }else {
                    $('.next-page a').attr('href', '');
                }

                var html = $result.html();

                $('#all-posts').append(html);

                // $(html).insertBefore($(".load-more-wrapper"))

                $('.load-more-btn').text('load more');



                $('.mobile-readmore-button').not('.has-event').click(function(event){

                    event.preventDefault();
                    console.log('.mobile-readmore-button clicked again');

                    var target = $(event.currentTarget);
                    var parent = target.parent();
                    parent.toggleClass('expanded-version');
                });


            });
        }

        $('.load-more-btn').on('click', function(e){        
            e.preventDefault();

            $(this).text('Loading...');

            var link = $('.next-page a').attr('href');

            if(link!='') {
                sendLoadMoreProductsRequestHOMEPAGE(link);       
            }else {
                $(this).parent().parent().hide();
            }
        });


        $('input[name="product_type"]').on('change', function(e) {
            if ($(this).is(':checked')) {
                setGetParameter('product_type', $(this).val());
            }else {
                console.log('unchecked')
            }
        });

        // product list
        $('.all-products').on({
            mouseenter: function() {
                $(this).find('.cta-list').addClass('show');
                $(this).find('.img').addClass('hover');
            },
            mouseleave: function() {
                $(this).find('.cta-list').removeClass('show');
                $(this).find('.img').removeClass('hover');
            }
        }, '.product-image-container');   

        $('.all-products').on({
            mouseenter: function() {
                $(this).parent().parent().find('.img').addClass('hover');
            },
            mouseleave: function() {                
                $(this).parent().parent().find('.img').removeClass('hover');
            }
        }, '.cta-list');  

        $('#popular-products').on({
            mouseenter: function() {
                console.log($(this).data('slick-index'))
                $(this).find('.cta-list').addClass('show');
                $(this).find('img').addClass('hover');
            },
            mouseleave: function() {
                $(this).find('.cta-list').removeClass('show');
                $(this).find('img').removeClass('hover');
            }
        }, '.center-text');   

        $('#popular-products').on({
            mouseenter: function() {
                $(this).parent().find('img').addClass('hover');
            },
            mouseleave: function() {                
                $(this).parent().find('img').removeClass('hover');
            }
        }, '.cta-list'); 

        $('#feature-products-inside').on({
            mouseenter: function() {
                console.log($(this).data('slick-index'))
                $(this).find('.cta-list').addClass('show');
                $(this).find('img').addClass('hover');
            },
            mouseleave: function() {
                $(this).find('.cta-list').removeClass('show');
                $(this).find('img').removeClass('hover');
            }
        }, '.each-feature');   

        $('#feature-products-inside').on({
            mouseenter: function() {
                $(this).parent().find('img').addClass('hover');
            },
            mouseleave: function() {                
                $(this).parent().find('img').removeClass('hover');
            }
        }, '.cta-list');  

        // header menus
        $('.cta-list .currency').on('click', function() {
            $('.currency-select').toggle();
            $('.search-select').hide();
            $('.account-select').hide();
            $('.cart-preview-select').hide();
        });

        $('.cta-list .search').on('click', function() {
            $('.currency-select').hide();
            $('.account-select').hide();
            $('.cart-preview-select').hide();
            $('.search-select').toggle();
        });

        $('.cta-list .account').on('click', function() {
            $('.search-select').hide();
            $('.currency-select').hide();
            $('.cart-preview-select').hide();
            $('.account-select').toggle();
        });

        $('.cta-list .cart').on('click', function() {
            $('.search-select').hide();
            $('.currency-select').hide();
            $('.account-select').hide();
            $('.cart-preview-select').toggle();
        });

        $('.currency-select li a').on('click', function(){
            var currencyCode = $(this).attr('id');
            $("#select-currency option:contains(" + currencyCode + ")").attr('selected', 'selected').trigger("change");           
        });

        try{
            $(".fancybox").fancybox();  
        } catch(e){
            console.log('fancy box jquery not loaded!!')
        }

        // product details qty
        $('.plus').on('click',function(e){
            e.preventDefault();
            var $qty=$(this).parent().find('.qty');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal)) {
                $qty.val(currentVal + 1);
            }
        });
        $('.minus').on('click',function(e){
            e.preventDefault();
            var $qty=$(this).parent().find('.qty');
            var currentVal = parseInt($qty.val());
            if (!isNaN(currentVal) && currentVal > 0) {
                $qty.val(currentVal - 1);
            }
        });       

        // // gift card specific
        // $('.orange').html($('.j2t-loyalty-points').html());

        // note that month is 0-based, like in the Date object. Adjust if necessary.
        function daysInMonth(month,year) {
            return new Date(year, month, 0).getDate();
        }

        $('#registration_date_day, #registration_date_year').on('change', function(e){
            var day = $('#registration_date_day').val();
            var month = $('#registration_date_month').val();
            var year = $('#registration_date_year').val();

            $('#day_to_send').val(month+'/'+day+'/'+year);
        });

        $('#registration_date_month').on('change', function(e){
            var day = $('#registration_date_day').val();
            var month = $(this).val();
            var year = $('#registration_date_year').val();
            var numberOfDays = daysInMonth($(this).val(), year);            

            $('#registration_date_day').html('');
            for(i=1; i<=numberOfDays; i++) {                
                $('#registration_date_day').append($('<option />').val(i).html(i));
            }

            $('#day_to_send').val(month+'/'+day+'/'+year);
        });

        var day = $('#registration_date_day').val();
        var month = $('#registration_date_month').val();
        var year = $('#registration_date_year').val();

        $('#day_to_send').val(month+'/'+day+'/'+year);       

        for (i = new Date().getFullYear(); i <= new Date().getFullYear()+50; i++)
        {
            $('#registration_date_year').append($('<option />').val(i).html(i));
        }


        // cart gift voucher 
        $('input[name=giftvoucher]').attr('checked', true).triggerHandler('click'); 
        $('input[name=giftvoucher_credit]').attr('checked', true).triggerHandler('click'); 

        $('.apply_giftcard').on('click', function(e){
            $('#giftcard_shoppingcart_apply').find('button').trigger('click');    
        });        

        $('.cancel_giftcard').on('click', function(e){            
            window.location=$('#remove_card').attr('href');
        });        

        // cart billing/shipping dropdown
        $('#billing-address-select').on('change', function(e){            
            if($(this).val()!=""){
                billing.setAddress($(this).val());
            }else {
                // billing.fillForm(false);
                $('#billing-new-address-form').find('input').val('');
            }
        });

        $('#shipping-address-select').on('change', function(e){            
            if($(this).val()!=""){
                shipping.setAddress($(this).val());
            }else {
                $('#shipping-new-address-form').find('input').val('');
            }
        });

        ///////
        // $('select#billing-address-select option:last').attr("selected","selected").trigger('change');
        // $('select#shipping-address-select option:last').attr("selected","selected").trigger('change');


        $('.view-tin-details').on('click', function(e){
            e.preventDefault();
            var $that = $(this);
            var $link = $(this).parent().parent().find('.item-options');
            $link.slideToggle('slow', function(){
                if($link.css('display') !== 'none')
                    $that.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                else
                    $that.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
        });

        ////


        try{
            $(".rotate").textrotator({
                animation: "flipUp",
                //animation: "flip",
                separator: ",",
                speed: 8000
            });
        } catch(e){
            
        }


    });


    


    
    

    


})(jQuery);


