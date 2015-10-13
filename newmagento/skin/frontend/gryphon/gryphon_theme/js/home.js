jQuery(document).ready(function($) {

	var feed = new Instafeed({
        get: 'user',
        userId: 637296152,        
        accessToken: '637296152.467ede5.9bae6b7506ad4287b4ba40e25edcc7b0',
        limit: 4,
        after: function() {
        	console.log('loaded image');
        	// For use within normal web clients 
	        var isiPad = navigator.userAgent.match(/iPad/i) != null;
	        // var isiPadTest = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);
	        if(isiPad) {
	            console.log('ipad');
	            $('#instafeed').slick({
	                dots: false,
	                infinite: true,
	                speed: 300,
	                slidesToShow: 3
	            });
	        }      
        }
    });
    feed.run();

});