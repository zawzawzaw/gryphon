/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  /////////////////////////////////////
  // GryphonMobileStoreSidebar Class //
  /////////////////////////////////////

  function GryphonMobileStoreSidebar(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    // CONTAINERS
    this.sort_container = this.element.find('#store-sort-expandable-container');
    this.filter_container = this.element.find('#store-filter-expandable-container');
    this.detail_container = this.element.find('#store-filter-detail-container');

    this.product_list_container = $('#content-wrapper.store .main-content .all-products');

    this.store_filter_detail_dictionary = [];

    this.open_state = "close";

    this.create_html();
    this.init();
  }

  GryphonMobileStoreSidebar.prototype = {
    init: function () {
      console.log("init");
      


      this.filter_checkbox_container = this.element.find('#store-filter-expandable-container #store-filter-expandable-checkboxes');
      this.filter_title_container = this.element.find('#store-filter-expandable-container #store-filter-expandable-title-container');


      // BUTTONS
      this.main_buttons = this.element.find('#store-filter-button-container .store-filter-button');
      this.main_sort_button = this.element.find('#store-filter-button-container #gryphon-store-sort-by-button');
      this.main_filter_button = this.element.find('#store-filter-button-container #gryphon-store-refine-button');

      this.sort_buttons = this.sort_container.find('ul li a');
      this.filter_title_buttons = this.filter_title_container.find('ul li a');

      this.clear_filter_button = this.element.find('#gryphon-store-clear-all-filter-button');
      this.apply_filter_button = this.element.find('#gryphon-store-apply-filter-button');

      this.detail_clear_buttons = this.element.find('.store-filter-detail-clear-button');
      this.detail_apply_buttons = this.element.find('.store-filter-detail-apply-button');

      
      // elements
      this.store_filter_details = this.element.find('.store-filter-detail');
      

      // EVENT HANDLERS
      this.main_sort_button.click(this.on_main_sort_button_click.bind(this));
      this.main_filter_button.click(this.on_main_filter_button_click.bind(this));

      this.clear_filter_button.click(this.on_clear_filter_button_click.bind(this));
      this.apply_filter_button.click(this.on_apply_filter_button_click.bind(this));

      this.sort_buttons.click(this.on_sort_buttons_click.bind(this));
      this.filter_title_buttons.click(this.on_filter_title_buttons_click.bind(this));


      this.detail_apply_buttons.click(this.on_detail_apply_buttons_click.bind(this));

    },


    //    _   _ _____ __  __ _     
    //   | | | |_   _|  \/  | |    
    //   | |_| | | | | |\/| | |    
    //   |  _  | | | | |  | | |___ 
    //   |_| |_| |_| |_|  |_|_____|
    //                             

    create_html: function(){

      // clone sort items
      $('#store-sort-expandable-container').append($('#content-wrapper.store #result_products .sort-by').clone());

      // clone 1st set of filter checkboxes (new featured best selling promo)
      $('#store-filter-expandable-checkboxes').append($('' + $('#content-wrapper.store .side-bar .each-filter-section .all-inputs').html() ) );



      // clone titles of each filter section
      var title_container_fragment = $(document.createDocumentFragment());
      var title_element = null;
      var li_element = null;

      var arr = $('#content-wrapper.store .side-bar .each-filter-section .title a');

      for (var i = 0, l = arr.length; i < l; i++) {
        title_element = $(arr[i]);
        

        li_element = $('<li></li>');
        li_element.append(title_element.clone());
        li_element.find('.fa').removeClass('fa-chevron-up').addClass('fa-chevron-right');

        title_container_fragment.append(li_element);
      }

      $('#store-filter-expandable-container #store-filter-expandable-title-container ul').append(title_container_fragment);
      $('#store-filter-expandable-container #store-filter-expandable-title-container ul .load-more').removeClass('load-more');


      // clone the collections
      var collection_element = null;
      var all_input_element = null;
      var collection_id = '';

      var store_filter_element = null;
      var store_filter_fragment = $(document.createDocumentFragment());
      var store_filter_detail_template = [
        '<div class="store-filter-detail">',
          '<div id="store-filter-detail-cta-container">',
            '<div class="row">',
              '<div class="col-xs-6">',
                '<a class="store-filter-detail-cta-button store-filter-detail-clear-button">Clear All</a>',
              '</div>',
              '<div class="col-xs-6">',
                '<a class="store-filter-detail-cta-button store-filter-detail-apply-button">Apply</a>',
              '</div>',
            '</div>',
          '</div>',
        '</div>'
      ].join('');

      arr = $('#content-wrapper.store .side-bar .each-filter-section.has-subsection');


      for (var i = 0, l = arr.length; i < l; i++) {
        collection_element = $(arr[i]);
        all_input_element = collection_element.find('.all-inputs');
        
        collection_id = '' + all_input_element.attr('id');

        store_filter_element = $('' + store_filter_detail_template);
        store_filter_element.prepend(all_input_element.clone());
        store_filter_fragment.append(store_filter_element);

        

        this.store_filter_detail_dictionary[collection_id] = store_filter_element;
      }

      $('#store-filter-detail-container').append(store_filter_fragment);
      $('#store-filter-detail-container').find('.all-inputs').show(0);
      

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
        // this.product_list_container.show(0);

        this.sort_container.stop(0).hide(0);
        this.filter_container.stop(0).hide(0);
        this.detail_container.stop(0).hide(0);

        this.main_buttons.removeClass('selected');

        $(window).scrollTop(0);
      }
    },

    show_sort: function(){
      this.sort_container.stop(0).slideDown(500);
      this.filter_container.stop(0).slideUp(500);
      this.detail_container.stop(0).slideUp(500);

      this.main_buttons.removeClass('selected');
      this.main_sort_button.addClass('selected');
    },
    show_filter: function(){
      this.sort_container.stop(0).slideUp(500);
      this.filter_container.stop(0).slideDown(500);
      this.detail_container.stop(0).slideUp(500);

      this.main_buttons.removeClass('selected');
      this.main_filter_button.addClass('selected');
    },
    show_detail: function(){
      this.sort_container.stop(0).slideUp(500);
      this.filter_container.stop(0).slideUp(500);
      this.detail_container.stop(0).slideDown(500);

      this.main_buttons.removeClass('selected');
      this.main_filter_button.addClass('selected');
    },
    

    //    __  __  ___  _   _ ____  _____   _______     _______ _   _ _____ ____  
    //   |  \/  |/ _ \| | | / ___|| ____| | ____\ \   / / ____| \ | |_   _/ ___| 
    //   | |\/| | | | | | | \___ \|  _|   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |  | | |_| | |_| |___) | |___  | |___  \ V / | |___| |\  | | |  ___) |
    //   |_|  |_|\___/ \___/|____/|_____| |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                                                           


    on_main_sort_button_click: function(event) {
      event.preventDefault();

      this.open();
      this.show_sort();
    },
    on_main_filter_button_click: function(event) {
      event.preventDefault();

      this.open();
      this.show_filter();
    },
    on_clear_filter_button_click: function(event) {
      event.preventDefault();

      // clear all the filters
    },
    on_apply_filter_button_click: function(event) {
      event.preventDefault();

      // apply all checked filters to the list
      this.close();
    },

    on_sort_buttons_click: function(event) {
      event.preventDefault();

      var target = $(event.currentTarget);

      // do the sort algo

      this.close();
    },

    on_filter_title_buttons_click: function(event) {
      event.preventDefault();

      var target = $(event.currentTarget);
      var href = target.attr('href');

      //console.log('on_filter_title_buttons_click: ' + href);

      href = href.split('#').join('');

      console.log('on_filter_title_buttons_click: ' + href);
      var element = this.store_filter_detail_dictionary[href];

      console.log(element);

      if (element != undefined && element != null) {
        this.store_filter_details.hide(0);
        element.show(0);

        this.show_detail();
      }


    },
    on_detail_apply_buttons_click: function(event){
      // this.show_filter();
    }

  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_mobile_store_sidebar'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_mobile_store_sidebar')) {
        $.data(this, 'gryphon_mobile_store_sidebar', new GryphonMobileStoreSidebar(this, settings));
      }
    });
  };

}(jQuery));