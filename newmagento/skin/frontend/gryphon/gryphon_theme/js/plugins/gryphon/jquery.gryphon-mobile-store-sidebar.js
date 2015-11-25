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

    this.store_filter_detail_array = [];
    this.store_filter_detail_dictionary = [];

    this.store_filter_detail_title_array = [];
    this.store_filter_detail_title_dictionary = [];

    this.open_state = "close";
    this.filter_state = "none";

    this.window = $(window);

    this.mobile_header = $('#main-mobile-header');

    this.num_of_filter_items = 0;

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

      //store-filter-button-container


      this.mobile_header.addClass('store-sidebar-version');

      this.window.scroll(this.on_window_scroll.bind(this));

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
      var title_href = "";
      var i_element = null;

      var arr = $('#content-wrapper.store .side-bar .each-filter-section .title a');

      for (var i = 0, l = arr.length; i < l; i++) {
        title_element = $(arr[i]);
        

        li_element = $('<li></li>');
        li_element.append(title_element.clone());

        i_element = li_element.find('.fa');
        i_element.removeClass('fa-angle-up')
                .addClass('fa-chevron-right')
                .appendTo(li_element);


        title_href = li_element.find('a').attr('href');
        title_href = title_href.split('#').join('');

        li_element.data('detail_id',title_href);
        

        this.store_filter_detail_title_array[i] = li_element;
        this.store_filter_detail_title_dictionary[title_href] = li_element;

        title_container_fragment.append(li_element);
      }

      $('#store-filter-expandable-container #store-filter-expandable-title-container ul').append(title_container_fragment);
      $('#store-filter-expandable-container #store-filter-expandable-title-container ul .load-more').removeClass('load-more');


      // clone the collections
      var collection_element = null;
      var all_input_element = null;
      var collection_id = '';


      var store_filter_element_apply_button = null;

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

      this.num_of_filter_items = arr.length;

      for (var i = 0, l = arr.length; i < l; i++) {
        collection_element = $(arr[i]);
        all_input_element = collection_element.find('.all-inputs');
        
        collection_id = '' + all_input_element.attr('id');

        store_filter_element = $('' + store_filter_detail_template);
        store_filter_element.prepend(all_input_element.clone());

        store_filter_element_apply_button = store_filter_element.find('.store-filter-detail-apply-button');
        store_filter_element_apply_button.data('detail_id', collection_id);

        store_filter_fragment.append(store_filter_element);


        this.store_filter_detail_array[i] = store_filter_element;
        this.store_filter_detail_dictionary[collection_id] = store_filter_element;
      }

      $('#store-filter-detail-container').append(store_filter_fragment);
      $('#store-filter-detail-container').find('.all-inputs').show(0);




      // update for already selected items
      var temp_id = null;
      var detail_element = null;
      
      for (var i = 0, l=this.store_filter_detail_title_array.length; i < l; i++) {
        detail_title_li = $(this.store_filter_detail_title_array[i]);
        temp_id = detail_title_li.data('detail_id');

        detail_element = this.store_filter_detail_dictionary[temp_id];


        // if detail has anything selected...
        if (detail_element.find('input:checkbox:checked').length > 0) {
          detail_title_li.addClass('selected');

          var arr = detail_element.find('input:checkbox:checked');
          var arr_item = null;
          var item_selected_arr = [];
          var item_selected_str = "";
          var label_str = '';
          for (var i = 0, l = arr.length; i < l; i++) {
            arr_item = $(arr[i]);
            label_str = arr_item.parent().find('.label-text').html();
            item_selected_arr[i] = label_str;
          }
          item_selected_str += item_selected_arr.join(', ');

          detail_title_li.append('<div class="items-selected">' + item_selected_str + '</div>');

        } else {
          detail_title_li.removeClass('selected');
          detail_title_li.find('.items-selected').remove();
        }
      }



      

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
        // this.product_list_container.show(0);

        this.sort_container.stop(0).hide(0);
        this.filter_container.stop(0).hide(0);
        this.detail_container.stop(0).hide(0);

        this.main_buttons.removeClass('selected');

        this.product_list_container.css({'display':'inline-block'});

        //$(window).scrollTop(0);
        $(window).scrollTop(261);
      }
    },

    show_sort: function(){
      this.filter_state = "sort";
      this.sort_container.stop(0).slideDown(500);
      this.filter_container.stop(0).slideUp(500);
      this.detail_container.stop(0).slideUp(500);

      this.main_buttons.removeClass('selected');
      this.main_sort_button.addClass('selected');

      this.product_list_container.css({'display': 'inline-block'});

      $(window).scrollTop(261);
    },
    show_filter: function(){
      this.filter_state = "filter";
      this.sort_container.stop(0).slideUp(500);

      this.filter_container.stop(0).slideDown(500);
      this.detail_container.stop(0).slideUp(500);

      this.main_buttons.removeClass('selected');
      this.main_filter_button.addClass('selected');

      this.product_list_container.css({'display': 'none'});

      //$(window).scrollTop(261);
      setTimeout(function(){
        $(window).scrollTop(261);
      },300)
    },
    show_detail: function(){
      this.filter_state = "detail";
      this.sort_container.stop(0).slideUp(500);
      this.filter_container.stop(0).slideUp(500);
      this.detail_container.stop(0).slideDown(500);

      this.main_buttons.removeClass('selected');
      this.main_filter_button.addClass('selected');
    },

    show_first_detail: function(){
      var detail_to_show = this.store_filter_detail_array[0];
      detail_to_show.show(0);
      this.show_detail();
    },
    remove_all_item_selected: function(){
      //store_filter_detail_title_dictionary
      
      var item = null;
      
      for (var i = 0, l=this.store_filter_detail_title_array.length; i < l; i++) {
        item = $(this.store_filter_detail_title_array[i]);
        item.removeClass('selected');
        item.find('.items-selected').remove();
      }


    },

    //    __  __  ___  _   _ ____  _____   _______     _______ _   _ _____ ____  
    //   |  \/  |/ _ \| | | / ___|| ____| | ____\ \   / / ____| \ | |_   _/ ___| 
    //   | |\/| | | | | | | \___ \|  _|   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |  | | |_| | |_| |___) | |___  | |___  \ V / | |___| |\  | | |  ___) |
    //   |_|  |_|\___/ \___/|____/|_____| |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                                                           


    on_main_sort_button_click: function(event) {
      event.preventDefault();

      /*
      this.open();
      this.show_sort();
      */
      if (this.open_state == "open" && this.filter_state == "sort") {
        this.close();
      } else {
        this.open();
        this.show_sort();
      }
    },
    on_main_filter_button_click: function(event) {
      event.preventDefault();

      /*
      this.open();
      this.show_filter();
      */

      if (this.open_state == "open" && this.filter_state == "filter") {
        this.close();
      } else {
        this.open();
        console.log('this.num_of_filter_items: ' + this.num_of_filter_items);
        if (this.num_of_filter_items == 1){
          this.show_first_detail();
        } else {
          this.show_filter();
        }
      }

    },
    on_clear_filter_button_click: function(event) {
      event.preventDefault();

      this.remove_all_item_selected();

      // clear all the filters
    },
    on_apply_filter_button_click: function(event) {              // command for this is already in inline html. this is all display related actions
      event.preventDefault();

      // apply all checked filters to the list
      this.close();
    },

    on_sort_buttons_click: function(event) {
      //event.preventDefault();
      //var target = $(event.currentTarget);
      // do the sort algo
      //this.close();
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
    on_detail_apply_buttons_click: function(event){                // command for this is already in inline html. this is all display related actions

      var target = $(event.currentTarget);
      var detail_id = target.data('detail_id');


      console.log('this.num_of_filter_items: ' + this.num_of_filter_items);

      if (this.num_of_filter_items == 1){
        this.close();
      } else {

        console.log('detail_id: ' + detail_id);
        if(detail_id != undefined && detail_id != null){



          var detail_title_li = this.store_filter_detail_title_dictionary[detail_id];
          var detail_element = this.store_filter_detail_dictionary[detail_id];

          // if detail has anything selected...
          if (detail_element.find('input:checkbox:checked').length > 0) {
            detail_title_li.addClass('selected');

            var arr = detail_element.find('input:checkbox:checked');
            var arr_item = null;
            var item_selected_arr = [];
            var item_selected_str = "";
            var label_str = '';
            for (var i = 0, l = arr.length; i < l; i++) {
              arr_item = $(arr[i]);
              label_str = arr_item.parent().find('.label-text').html();
              item_selected_arr[i] = label_str;
            }
            item_selected_str += item_selected_arr.join(', ');

            detail_title_li.append('<div class="items-selected">' + item_selected_str + '</div>');

          } else {
            detail_title_li.removeClass('selected');
            detail_title_li.find('.items-selected').remove();
          }
          
        }

        this.show_filter();
      }

    },

    on_window_scroll: function(event){

      var scroll_top = this.window.scrollTop();

      console.log(scroll_top);

      //340 = 257 + 83

      //if (scroll_top > 340) { 
      if (scroll_top > 257) {
        this.element.addClass('sticky-version');
      } else {
        this.element.removeClass('sticky-version');
      }

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