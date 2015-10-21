/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  /////////////////////////
  // GryphonMobileWPSidebar Class //
  /////////////////////////

  function GryphonMobileWPSidebar(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.open_state = "close";
    this.filter_state = "none";
    this.window = $(window);


    // buttons
    this.main_buttons = this.element.find('#mobile-blog-button-container .mobile-blog-button');
    this.main_tag_button = this.element.find('#gryphon-blog-tag-button');
    this.main_category_button = this.element.find('#gryphon-blog-categories-button');

    this.tag_container = this.element.find('#mobile-blog-tag-container');
    this.category_container = this.element.find('#mobile-blog-category-container');

    $('#main-mobile-header').addClass('store-sidebar-version');
    

    // no need to create html
    this.init();
  }

  GryphonMobileWPSidebar.prototype = {
    init: function () {

      this.main_tag_button.click(this.on_main_tag_button_click.bind(this));
      this.main_category_button.click(this.on_main_category_button_click.bind(this));


      this.window.scroll(this.on_window_scroll.bind(this));

      console.log("init");
    },

    //    ____  _   _ ____  _     ___ ____ 
    //   |  _ \| | | | __ )| |   |_ _/ ___|
    //   | |_) | | | |  _ \| |    | | |    
    //   |  __/| |_| | |_) | |___ | | |___ 
    //   |_|    \___/|____/|_____|___\____|
    //                                     

    open: function(){
      if (this.open_state != "open") {
        this.open_state = "open";
        // this.product_list_container.css('display', 'inline-block');
      }
    },
    close: function(){
      if (this.open_state != "close") {
        this.open_state = "close";
        this.filter_state = "none";
        
        this.tag_container.stop(0).hide(0);
        this.category_container.stop(0).hide(0);
        this.main_buttons.removeClass('selected');

        $(window).scrollTop(0);
      }
    },

    show_tags: function(){
      this.filter_state = "tags";

      this.tag_container.stop(0).slideDown(500);
      this.category_container.stop(0).slideUp(500);

      this.main_buttons.removeClass('selected');
      this.main_tag_button.addClass('selected');
    },
    show_category: function(){
      this.filter_state = "category";

      this.tag_container.stop(0).slideUp(500);
      this.category_container.stop(0).slideDown(500);

      this.main_buttons.removeClass('selected');
      this.main_category_button.addClass('selected');
    },
    
    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          

    on_main_tag_button_click: function(event){
      event.preventDefault();

      if (this.open_state == "open" && this.filter_state == "tags") {
        this.close();
      } else {
        this.open();
        this.show_tags();
      }

    },
    on_main_category_button_click: function(event){
      event.preventDefault();

      if (this.open_state == "open" && this.filter_state == "category") {
        this.close();
      } else {
        this.open();
        this.show_category();
      }
    },
    on_window_scroll: function(event){
      var scroll_top = this.window.scrollTop();

      //340 = 257 + 83

      //if (scroll_top > 340) { 
      //if (scroll_top > 257) {
      //if (scroll_top > 227) {
      if (scroll_top > (277 + 9)) {
        this.element.addClass('sticky-version');
      } else {
        this.element.removeClass('sticky-version');
      }

    }
  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_mobile_wp_sidebar'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_mobile_wp_sidebar')) {
        $.data(this, 'gryphon_mobile_wp_sidebar', new GryphonMobileWPSidebar(this, settings));
      }
    });
  };

}(jQuery));