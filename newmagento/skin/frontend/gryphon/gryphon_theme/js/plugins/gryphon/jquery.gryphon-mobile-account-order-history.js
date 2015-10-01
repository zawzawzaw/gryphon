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


    this.top_part = this.element.find('#mobile-order-history-top-part');
    this.top_part_02 = $('.order-history-heading');
    this.bottom_part = this.element.find('#mobile-order-history-bottom-part');

    this.all_orders = this.element.find('.single-order-item');

    this.back_button = this.element.find('.single-order-item .single-order-item-back-button');

    this.order_array = [];
    this.order_dictionary = [];

    this.button_array = [];
    this.button_dictionary = [];

    this.init();
  }

  GryphonMobileAccountOrderHistory.prototype = {
    init: function () {
      console.log("init");

      this.create_order_array();

      this.back_button.click(this.on_back_click.bind(this));
    },

    create_order_array: function(){
      
      var arr = this.element.find('.single-order-item');
      var item = null;
      var item_id = '';

      var button = null;
      
      for (var i = 0, l=arr.length; i < l; i++) {

        console.log('created: ' + i);

        item = $(arr[i]);
        item_id = item.attr('data-id');
        button = item.find('.mobile-each-order-open');
        button = item.find('.single-order-item-mobile-button');

        button.click(this.on_order_item_open_click.bind(this));
        button.data('orderid', item_id);

        this.order_array[i] = item;
        this.order_dictionary[item_id] = item;
        this.button_array[i] = button;
        this.button_dictionary[item_id] = button;
      }
    },

    //    ____  _   _ ____  _     ___ ____ 
    //   |  _ \| | | | __ )| |   |_ _/ ___|
    //   | |_) | | | |  _ \| |    | | |    
    //   |  __/| |_| | |_) | |___ | | |___ 
    //   |_|    \___/|____/|_____|___\____|
    //                                     

    close_all_orders: function(){
      this.all_orders.removeClass('collapsed expanded');
      /*
      this.top_part.show(0);
      this.top_part_02.show(0);
      this.bottom_part.show(0);
      */
      this.top_part.stop(0).slideDown(500);
      this.top_part_02.stop(0).slideDown(500);
      this.bottom_part.stop(0).slideDown(500);
    },
    open_order: function(str_param){
      var order = this.order_dictionary[str_param];

      if (order != null && order != undefined){

        this.all_orders.removeClass('expanded');
        this.all_orders.addClass('collapsed');

        /*
        this.top_part.hide(0);
        this.top_part_02.hide(0);
        this.bottom_part.hide(0);
        */
        this.top_part.stop(0).slideUp(500);
        this.top_part_02.stop(0).slideUp(500);
        this.bottom_part.stop(0).slideUp(500);

        order.removeClass('collapsed');
        order.addClass('expanded');

        $(window).scrollTop(0);
      }
    },

    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          
    on_order_item_open_click: function(event){

      event.preventDefault();
      var target = $(event.currentTarget);
      var item_id = target.data('orderid');

      console.log('item_id: ' + item_id);

      this.open_order(item_id);
    },
    on_back_click: function(event){
      this.close_all_orders();
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