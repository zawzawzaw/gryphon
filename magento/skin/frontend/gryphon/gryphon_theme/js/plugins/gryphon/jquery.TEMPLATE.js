/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  /////////////////////////
  // NAME_OF_CLASS Class //
  /////////////////////////

  function NAME_OF_CLASS(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);
    this.init();
  }

  NAME_OF_CLASS.prototype = {
    init: function () {
      console.log("init");
    }
  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['NAME_OF_PLUGIN'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'NAME_OF_PLUGIN')) {
        $.data(this, 'NAME_OF_PLUGIN', new NAME_OF_CLASS(this, settings));
      }
    });
  };

}(jQuery));