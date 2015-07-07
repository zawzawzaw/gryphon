var Customcheckout = Class.create(Checkout, {
	initialize: function($super,accordion, urls){
		$super(accordion, urls);
		//Merged billing and shipping, so no need shipping step
        //No need payment method too
		this.steps = ['login','billing','review'];
	},
    gotoSection: function (section, reloadProgressBlock) {
        if (reloadProgressBlock) {
            this.reloadProgressBlock(this.currentStep);
        }
        this.currentStep = section;
        var sectionElement = $('opc-' + section);
        sectionElement.addClassName('allow');
        this.accordion.openSection('opc-' + section);
        this.gotoTab(section);
        if(!reloadProgressBlock) {
            this.resetPreviousSteps();
        }
    },
    gotoTab: function(currentItem) {
        jQuery('.cart .cart-breadcrumb a').removeClass('active');
        if (currentItem == 'billing' || currentItem == 'shipping' || currentItem == "shipping_method" || currentItem == 'payment') {
            if (jQuery('#op-billing-shipping').size()) {
                jQuery('#op-billing-shipping').addClass('active');
            }
        } else if (currentItem == 'review') {
            if (jQuery('#op-confirmation').size()) {
                jQuery('#op-confirmation').addClass('active');
            }
        }
    }
});
var Customshipping = Class.create(Shipping, {
    syncWithBilling: function ($super) {
        $super();
        $('shipping:same_as_billing').checked = true;
        if (jQuery("#shipping\\:country_id").size()) {
            if (jQuery("#shipping\\:country_id").is(':visible')) {
                jQuery("#shipping\\:country_id").trigger('change');
            }
        }
        if (jQuery('#shipping-address-select').size()) {
            jQuery("#shipping-address-select").trigger('change');
        }
        $('shipping:same_as_billing').checked = true;
    }
});