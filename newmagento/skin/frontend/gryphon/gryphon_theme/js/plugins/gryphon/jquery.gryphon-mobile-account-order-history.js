/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  ////////////////////////////////////////////
  // GryphonMobileAccountOrderHistory Class //
  ////////////////////////////////////////////

  function GryphonMobileAccountOrderHistory(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);
    this.init();
  }

  GryphonMobileAccountOrderHistory.prototype = {
    init: function () {
      console.log("init");
    }
  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_mobile_account_order_history'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_mobile_account_order_history')) {
        $.data(this, 'gryphon_mobile_account_order_history', new GryphonMobileAccountOrderHistory(this, settings));
      }
    });
  };

}(jQuery));