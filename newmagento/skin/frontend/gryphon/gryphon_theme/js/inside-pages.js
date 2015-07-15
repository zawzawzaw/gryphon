var initialLoad = true;
jQuery(document).ready(function(){		
    jQuery(window).on('scroll', function() {
        var scrollPos = jQuery(window).scrollTop();
        
        if( ( scrollPos != 0 ) ) {
            console.log('scrolling')
            jQuery('#header-wrapper').addClass('shadow');
        }       
        else if( ( scrollPos === 0 ) ) {
            jQuery('#header-wrapper').removeClass('shadow');
        }
    });

    initialLoad = false;
});