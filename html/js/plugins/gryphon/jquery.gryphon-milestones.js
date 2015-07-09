/**
 * Gryphon Milestone
 * 
 * @author Jairus
 */

(function ($) {

  var defaults = {
    width: 280,
    gutter: 20,
    auto_play: false
  };

    ///////////////////////////////
   //  GryphonMilestones Class  //
  ///////////////////////////////

  function GryphonMilestones(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.item_width = this.settings['width'] + this.settings['gutter'];
    this.auto_play = this.settings['auto_play'];

    this.max_col = 4; // 4 by default
    this.max_index = 0;
    this.max_page = 0;
    this.page_width = 0;

    this.milestone_array = [];
    this.circle_array = [];

    this.current_index = 0;
    this.current_page_index = 0;

    this.milestone_container = this.element.find('.milestone-container');
    this.circle_container = this.element.find('ul.circle-container');
    this.timeline_element = this.element.find('.timeline');

    this.red_line = this.element.find('.red-line');

    this.prev_button = this.element.find('.prev-button');
    this.prev_button.click(this.on_prev_button_click.bind(this));

    this.next_button = this.element.find('.next-button');
    this.next_button.click(this.on_next_button_click.bind(this));


    this.init();



    this.highlight_index(0);
    this.goto_page(0);

    if (this.auto_play) {
      TweenMax.delayedCall(5, this.auto_play_function, [], this);
    }

  }

  GryphonMilestones.prototype = {
    init: function () {
      console.log("init");

      // CREATE MILESTONE ARRAY
      // CREATE CIRCLE ARRAY
      var arr = this.element.find('.milestone');
      var milestone_element = null;
      var circle_element = null;

      var circle_container_fragment = $(document.createDocumentFragment());
      
      for (var i = 0, l = arr.length; i < l; i++) {

        milestone_element = $(arr[i]);
        milestone_element.data('i', i);
        milestone_element.click(this.on_milestone_click.bind(this));

        circle_element = $('<li class="fa fa-circle"></li>');
        circle_element.data('i', i);
        circle_element.click(this.on_circle_element_click.bind(this));

        this.milestone_array[i] = milestone_element;
        this.circle_array[i] = circle_element;

        circle_container_fragment.append(circle_element);
      }

      this.max_index = this.milestone_array.length - 1;

      // need the array's length before determining max pages
      this.window = $(window);
      this.window.resize(this.on_window_resize.bind(this));
      this.on_window_resize(null);

      this.circle_container.append(circle_container_fragment);

    },

    //    ____  ____  _____     ___  _____ _____ 
    //   |  _ \|  _ \|_ _\ \   / / \|_   _| ____|
    //   | |_) | |_) || | \ \ / / _ \ | | |  _|  
    //   |  __/|  _ < | |  \ V / ___ \| | | |___ 
    //   |_|   |_| \_\___|  \_/_/   \_\_| |_____|
    //                                           

    auto_play_function: function(){


      if (this.auto_play == true) {         // turned off on interaction

        var target_index = this.current_index + 1;

        target_index = target_index > this.max_index ? 0 : target_index;    // reset to zero if over


        var target_page = Math.floor( target_index / this.max_col );

        this.highlight_index(target_index);
        this.goto_page(target_page);

        TweenMax.delayedCall(2, this.auto_play_function, [], this);
      }


    },

    //    ____  _   _ ____  _     ___ ____ 
    //   |  _ \| | | | __ )| |   |_ _/ ___|
    //   | |_) | | | |  _ \| |    | | |    
    //   |  __/| |_| | |_) | |___ | | |___ 
    //   |_|    \___/|____/|_____|___\____|
    //                                     

    goto_page: function(number_param){

      if (number_param >= 0 && number_param <= this.max_page) {

        this.current_page_index = number_param;

        var target_x = -1 * (this.max_col * this.item_width) * this.current_page_index;

        TweenMax.to(this.circle_container, 0.8, {left: target_x,ease: Sine.easeInOut});
        TweenMax.to(this.milestone_container, 0.8, {left: target_x,ease: Sine.easeInOut});
      }

    },
    highlight_index: function(number_param){

      if (number_param >= 0 && number_param <= this.max_index) { 

        this.current_index = number_param;

        // ANIMATE THE LINE
        var target_width = (number_param * this.item_width) + (this.settings['width'] / 2);
        TweenMax.to(this.red_line, 0.8, {width: target_width});

        // SELECT THE RED CIRCLE
        var milestone_element = null;
        var circle_element = null;

        for (var i = 0, l = this.milestone_array.length; i < l; i++) {

          milestone_element = $(this.milestone_array[i]);
          circle_element = $(this.circle_array[i]);

          if (i <= this.current_index) {
            circle_element.addClass('selected');
          } else {
            circle_element.removeClass('selected');
          }

          if (i == this.current_index) {
            milestone_element.addClass('selected');
          } else {
            milestone_element.removeClass('selected');
          }

        }

      }
      
    },



    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          

    on_window_resize: function(event){
      //var window_width = this.window.width();     // cannot use window width cause the component is confined to the width of the element
      var window_width = this.element.width();

      this.max_col = Math.floor(window_width / this.item_width );
      this.max_page = Math.ceil(this.milestone_array.length / this.max_col) - 1;        // minus 1 because arrays count starting from 0;

      this.page_width = (this.max_col * this.item_width) - this.settings['gutter'];

      this.timeline_element.width(this.page_width);

    },
    on_prev_button_click: function(event){
      event.preventDefault();
      this.auto_play = false;

      var target_index = this.current_page_index - 1;
      target_index = target_index <= 0 ? 0 : target_index;
      this.goto_page(target_index);


      var current_page_max_index = this.max_col * (target_index + 1) - 1;
      if (this.current_index > current_page_max_index) {
        this.highlight_index(current_page_max_index);
      }

    },
    on_next_button_click: function(event){
      event.preventDefault();
      this.auto_play = false;

      var target_index = this.current_page_index + 1;
      target_index = target_index >= this.max_page ? this.max_page : target_index;
      this.goto_page(target_index);

      var current_page_max_index = this.max_col * target_index
      if (this.current_index < current_page_max_index) {
        this.highlight_index(current_page_max_index);
      }
    },

    on_milestone_click: function(event){
      event.preventDefault();
      this.auto_play = false;

      var target = $(event.currentTarget);
      var index = target.data('i');

      this.highlight_index(index);

    },
    on_circle_element_click: function(event){
      event.preventDefault();
      this.auto_play = false;

      var target = $(event.currentTarget);
      var index = target.data('i');

      this.highlight_index(index);

    }

  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_milestones'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_milestones')) {
        $.data(this, 'gryphon_milestones', new GryphonMilestones(this, settings));
      }
    });
  };

}(jQuery));