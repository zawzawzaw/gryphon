/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  ///////////////////////////////
  // GryphonMobileHeader Class //
  ///////////////////////////////

  function GryphonMobileHeader(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.marquee_container = this.element.find("#mobile-marquee-container");
    this.expand_container = this.element.find("#mobile-expand-container");

    
    this.menu_button = this.element.find(".mobile-menu-button");
    this.search_button = this.element.find(".mobile-search-button");
    this.cart_button = this.element.find(".mobile-cart-button");
    this.account_expand_button = this.element.find("#mobile-account-expand-button");

    this.account_list_element = this.element.find("#mobile-account ul");

    this.currency_expand_container = this.element.find('#mobile-currency-expand-container');
    this.currency_expand_header = this.element.find('#mobile-currency-header');
    this.currency_expand_button = this.element.find('#mobile-currency-expand-button');

    

    this.submenu_elements = this.element.find(".mobile-submenu");
    this.submenu_expandable_elements = this.element.find(".mobile-submenu .has-sub-menu");
    this.submenu_expandable_list_elements = this.element.find(".mobile-submenu .has-sub-menu .sub-menu");
    

    this.search_expand_expandable = this.element.find("#mobile-search-expand-container");
    this.cart_expand_expandable = this.element.find("#mobile-cart-expand-container");


    this.cart_back_to_shopping_button = this.cart_expand_expandable.find('.cta-btns .continue-shopping');
    this.cart_back_to_shopping_button.click(this.on_cart_back_to_shopping_button_click.bind(this));
    
    


    this.initial_scroll = false;

    this.menu_open_state = "close";
    this.account_open_state = "close";
    this.currency_open_state = "close";

    this.cart_open_state = "close";
    this.search_open_state = "close";

    this.window = $(window);
    this.body_element = $('body');

    // move the element
    this.body_element.prepend($("#mobile-header-wrapper"));

    
    this.create_html();
    this.init();

  }

  GryphonMobileHeader.prototype = {

    create_html: function(){
      var new_currency_dropdown = $('#main-header .currency-switcher .currency-select.all-dropdown').clone();
      this.currency_expand_container.append(new_currency_dropdown);



    },
    init: function () {
      console.log("init");


      this.menu_button.click(this.on_menu_button_click.bind(this));
      this.account_expand_button.click(this.on_account_expand_button_click.bind(this));


      this.search_button.click(this.on_search_button_click.bind(this));
      this.cart_button.click(this.on_cart_button_click.bind(this));




      //var submenu_element = null;
      var submenu_expandable_element = null;

      for (var i = 0, l = this.submenu_expandable_elements.length; i < l; i++) {
        submenu_expandable_element = $(this.submenu_expandable_elements[i]);
        console.log(submenu_expandable_element)
        $(submenu_expandable_element.find("a")[0]).click(this.on_submenu_expandable_element_click.bind(this)); 
      }


      $("#page-wrapper").click(this.on_page_wrapper_click.bind(this));





      this.currency_expand_container.find('ul li a').click(this.on_currency_container_a_click.bind(this));

      this.currency_expand_button = this.element.find('#mobile-currency-expand-button');
      
      //this.currency_expand_button.click(this.on_currency_expand_button_click.bind(this));
      this.currency_expand_header.click(this.on_currency_expand_button_click.bind(this));


      //$(window).on('scroll', this.on_window_scroll.bind(this));
      //$("#page-wrapper").on('scroll', this.on_window_scroll.bind(this));
      
      this.window.resize(this.on_window_resize.bind(this));
      this.window.scroll(this.on_window_scroll.bind(this));

      this.update_body_class();

      // fix for browser reloading at scroll position
      // http://stackoverflow.com/questions/7035331/prevent-automatic-browser-scroll-on-refresh
      $(window).on('beforeunload', function() {
          $(window).scrollTop(0);
      });


      $(window).scrollTop(0);

    },

    

    //    ____  ____  _____     ___  _____ _____ 
    //   |  _ \|  _ \|_ _\ \   / / \|_   _| ____|
    //   | |_) | |_) || | \ \ / / _ \ | | |  _|  
    //   |  __/|  _ < | |  \ V / ___ \| | | |___ 
    //   |_|   |_| \_\___|  \_/_/   \_\_| |_____|
    //                                           

    //    ____  _   _ ____  _     ___ ____ 
    //   |  _ \| | | | __ )| |   |_ _/ ___|
    //   | |_) | | | |  _ \| |    | | |    
    //   |  __/| |_| | |_) | |___ | | |___ 
    //   |_|    \___/|____/|_____|___\____|
    //                                     

    open_menu: function(){

      if (this.menu_open_state != "open") {
        this.menu_open_state = "open";

        $("html").addClass('mobile-menu-open');
        //this.expand_container.stop(0).slideDown(1000);
        this.expand_container.show(0);

        this.window.scrollTop(0);


        //var is_account_page = $('body').hasClass('account-page-version');
        
        //customer-account-index
        //customer-address-form

        var body = $('body');
        var is_account_page = body.hasClass('customer-account');

        if (is_account_page) {
          this.open_account();
        }


      }

    },
    close_menu: function(){

      if (this.menu_open_state != "close") {
        this.menu_open_state = "close";

        $("html").removeClass('mobile-menu-open');
        //this.expand_container.stop(0).slideUp(1000);
        this.expand_container.hide(0);

        // additional
        this.close_account();
        this.close_currency();

        this.submenu_expandable_elements.removeClass('expanded');
        this.submenu_expandable_list_elements.stop(0).slideUp(500);

      }
    },

    open_account: function(){
      if(this.account_open_state != "open"){
        this.account_open_state = "open";
        this.account_list_element.stop(0).slideDown(500);
      }
    },
    close_account: function(){
      if(this.account_open_state != "close"){
        this.account_open_state = "close";
        this.account_list_element.stop(0).slideUp(500);
      }
    },


    open_currency: function(){
      if(this.currency_open_state != "open"){
        this.currency_open_state = "open";
        this.currency_expand_container.stop(0).slideDown(500);
        this.currency_expand_button.addClass('expanded');
      }
    },
    close_currency: function(){
      if(this.currency_open_state != "close"){
        this.currency_open_state = "close";
        this.currency_expand_container.stop(0).slideUp(500);
        this.currency_expand_button.removeClass('expanded');
      }
    },
    


    open_cart: function(){
      if(this.cart_open_state != "open"){
        this.cart_open_state = "open";

        /*
        this.cart_expand_expandable.stop(0).slideDown(500);
        $("html").addClass('mobile-black-menu-open');
        */
        $("html").addClass('mobile-menu-cart-open');
        
      }
    },
    close_cart: function(){
      if(this.cart_open_state != "close"){
        this.cart_open_state = "close";

        /*
        this.cart_expand_expandable.stop(0).slideUp(500);
        $("html").removeClass('mobile-black-menu-open');
        */
        
        $("html").removeClass('mobile-menu-cart-open');

      }
    },
    open_search: function(){
      if(this.search_open_state != "open"){
        this.search_open_state = "open";

        this.search_expand_expandable.stop(0).slideDown(500);
        $("html").addClass('mobile-black-menu-open');
      }
    },
    close_search: function(){
      if(this.search_open_state != "close"){
        this.search_open_state = "close";

        this.search_expand_expandable.stop(0).slideUp(500);
        $("html").removeClass('mobile-black-menu-open');
      }
    },

    update_body_class: function(){
      var window_width = this.window.width();
      if(window_width <= 991){
        // if mobile
        this.body_element.addClass('is-mobile');
      } else {
        // if desktop
        this.body_element.removeClass('is-mobile');
      }
    },



    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          

    on_window_scroll: function(){

      /*
      console.log('on_window_scroll');

      if(this.initial_scroll == false){
        this.initial_scroll = true;
        this.marquee_container.slideUp(500);
      }
      */
      
      var scroll_top = this.window.scrollTop();

      if(scroll_top > 10){
        this.element.addClass('shadow-version');
        this.body_element.addClass('shadow-version');
      } else {
        this.element.removeClass('shadow-version');
        this.body_element.removeClass('shadow-version');
      }

    },

    on_window_resize: function(){
      var window_width = this.window.width();

      this.update_body_class();


      if(window_width <= 991){
        // if mobile
      } else {
        // if desktop
        this.close_menu();
      }

    },

    //    __  __  ___  _   _ ____  _____   _______     _______ _   _ _____ 
    //   |  \/  |/ _ \| | | / ___|| ____| | ____\ \   / / ____| \ | |_   _|
    //   | |\/| | | | | | | \___ \|  _|   |  _|  \ \ / /|  _| |  \| | | |  
    //   | |  | | |_| | |_| |___) | |___  | |___  \ V / | |___| |\  | | |  
    //   |_|  |_|\___/ \___/|____/|_____| |_____|  \_/  |_____|_| \_| |_|  
    //                                                                     

    on_page_wrapper_click: function(event){
      if (this.search_open_state == "open") {
        this.close_search();
      }
      if (this.cart_open_state == "open") {
        this.close_cart();
      }
    },

    on_search_button_click: function(event) {
      event.preventDefault();

      this.close_menu();

      if (this.search_open_state == "close") {
        this.close_cart();
        this.open_search();

      } else {
        this.close_search();
      }
      
      
    },
    on_cart_button_click: function(event) {
      event.preventDefault();

      this.close_menu();

      if (this.cart_open_state == "close") {
        this.close_search();
        this.open_cart();

      } else {
        this.close_cart();
      }
    },
    public_open_cart: function(){
      this.close_menu();
      this.close_search();
      this.open_cart();
    },

    on_menu_button_click: function(event){
      event.preventDefault();

      // new functionality (for easier use)
      if (this.search_open_state == "open") {
        this.close_search();
      }
      if (this.cart_open_state == "open") {
        this.close_cart();
      }

      // toggle
      if (this.menu_open_state == "close") {
        this.open_menu();
      } else {
        this.close_menu();
      }
    },

    on_account_expand_button_click: function(event){
      event.preventDefault();

      if(this.account_open_state == "close"){
        this.open_account();
      } else {
        this.close_account();
      }
    },

    on_submenu_expandable_element_click: function(event){
      event.preventDefault();

      console.log('click')

      var target = $(event.currentTarget);
      var submenu = target.parent().find('.sub-menu');

      // close others
      this.submenu_expandable_elements.removeClass('expanded');
      this.submenu_expandable_list_elements.stop(0).slideUp(500);

      if (submenu.is(':visible') == false) {
        target.addClass('expanded');
        submenu.stop(0).slideDown(500);
      }

    },
    on_currency_expand_button_click: function(event){
      event.preventDefault();

      if(this.currency_open_state == "close"){
        this.open_currency();
      } else {
        this.close_currency();
      }
    },


    on_currency_container_a_click: function(event){
      event.preventDefault();
      var target = $(event.currentTarget);
      var currencyCode = target.attr('id');
      var currentIndex = target.parent().index();      
      
      var el = document.getElementById("select-currency");
      setLocation($(el.options[currentIndex]).val());
      // $("#select-currency option:contains(" + currencyCode + ")").attr('selected', 'selected').trigger("change");

      this.close_menu();
    },

    on_cart_back_to_shopping_button_click: function(event){
      event.preventDefault();
      this.close_cart();
    }


  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_mobile_header'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_mobile_header')) {
        $.data(this, 'gryphon_mobile_header', new GryphonMobileHeader(this, settings));
      }
    });
  };

}(jQuery));