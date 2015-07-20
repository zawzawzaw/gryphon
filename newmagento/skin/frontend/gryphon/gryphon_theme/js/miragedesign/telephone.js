(function($){
    $(function() {
        // $("#phone_no").intlTelInput({
        //     // utilsScript: "lib/intl-tel-input/lib/libphonenumber/build/utils.js"
        // });
    	$("#telephone").intlTelInput({
    		utilsScript: "lib/intl-tel-input/lib/libphonenumber/build/utils.js"
    	});
    	$("#phone_no").intlTelInput({
	        defaultCountry: 'sg',
	        nationalMode: false,
	        autoHideDialCode: false,
	        autoPlaceholder: false
	      });
    });
})(jQuery);