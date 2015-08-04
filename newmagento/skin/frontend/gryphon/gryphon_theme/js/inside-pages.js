var initialLoad = true;
jQuery(document).ready(function($){		
    function myScroller()  {
        var scrollPos = $(window).scrollTop();
        
        if( ( scrollPos != 0 ) ) {
            jQuery('#header-wrapper').addClass('shadow');
            $('.arrow').hide();
            if(scrolled==false && initialLoad==false) {
                scrolled = true;               
            }
                
        }       
        else if( ( scrollPos === 0 ) && (scrolled == true) ) {
            scrolled = false;
            $('#header-wrapper').removeClass('shadow');
            $('.arrow').show();
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
});