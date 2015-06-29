/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
  };

  //////////////////////////
  //   GryphonFAQ Class   //
  //////////////////////////

  function GryphonFAQ(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.faq_section_array = [];

    this.init();
  }

  GryphonFAQ.prototype = {
    init: function () {
      console.log("init");

      var arr = $('.faq-section');

      var faq_section_element = null;
      var faq_section = null;

      for (var i = 0, l = arr.length; i < l; i++) {
        faq_section_element = $(arr[i]);

        faq_section_element.gryphon_faq_section({
          i: i,
          on_open: this.on_faq_section_open.bind(this)
        });

        faq_section = faq_section_element.data('gryphon_faq_section');
        this.faq_section_array[i]  = faq_section;

      }

    },
    on_faq_section_open: function(number_param){

      faq_section = null;

      for (var i = 0, l = this.faq_section_array.length; i < l; i++) {
        faq_section = this.faq_section_array[i];

        if(number_param !=  i){
          faq_section.close_all();
        }

      }

      
    }

  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_faq'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_faq')) {
        $.data(this, 'gryphon_faq', new GryphonFAQ(this, settings));
      }
    });
  };



  ///////////////////////////////////////////////////
  //    _____ _    ___    ____  _____ ____ _____ ___ ___  _   _ 
  //   |  ___/ \  / _ \  / ___|| ____/ ___|_   _|_ _/ _ \| \ | |
  //   | |_ / _ \| | | | \___ \|  _|| |     | |  | | | | |  \| |
  //   |  _/ ___ \ |_| |  ___) | |__| |___  | |  | | |_| | |\  |
  //   |_|/_/   \_\__\_\ |____/|_____\____| |_| |___\___/|_| \_|
  //                                                            
  ////////////////////////////////////////////////////

  var faq_section_defaults = {
    i: 0,
    on_open: function(){}
  };

  /////////////////////////////////
  //   GryphonFAQSection Class   //
  /////////////////////////////////

  function GryphonFAQSection(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, faq_section_defaults, settings);

    this.li_array = [];

    this.current_index = -1;
    this.max_index = 0;

    this.i = this.settings['i'];

    this.init();

  }

  GryphonFAQSection.prototype = {
    init: function () {
      console.log("init");


      

      var arr = this.element.find('ul li');
      var li_element = null;
      var button_element = null;
      var question_element = null;
      var answer_element = null;
      var question_element_2 = null;

      for (var i = 0, l = arr.length; i < l; i++) {

        li_element = $(arr[i]);
        button_element = li_element.find('.arrow-btn');
        question_element = li_element.find('.question');
        answer_element = li_element.find('.answer');

        question_element_2 = $('<div class="question-2">' + question_element.html() + '</div>');
        answer_element.prepend(question_element_2);
        

        //question_element_2 = li_element.find('.question-2');

        button_element.data('index',i);
        question_element.data('index',i);
        question_element_2.data('index',i);

        button_element.click(this.on_question_element_clicked.bind(this));
        question_element.click(this.on_question_element_clicked.bind(this));
        question_element_2.click(this.on_question_element_clicked.bind(this));

        this.li_array[i] = li_element;

      }

      this.max_index = this.li_array.length - 1;
    },

    //    ____  _   _ ____  _     ___ ____ 
    //   |  _ \| | | | __ )| |   |_ _/ ___|
    //   | |_) | | | |  _ \| |    | | |    
    //   |  __/| |_| | |_) | |___ | | |___ 
    //   |_|    \___/|____/|_____|___\____|
    //                                     

    open_index: function(number_param){
      var li_element = null;

      if (number_param >= 0 && number_param <= this.max_index){

        this.current_index = number_param;

        for (var i = 0, l = this.li_array.length; i < l; i++) {

          li_element = this.li_array[i];

          if(this.current_index == i){
            li_element.addClass('open-version');
          } else {
            li_element.removeClass('open-version');
          }

        }

        this.settings['on_open'](this.i);

      }

    },
    close_all: function(){

      if (this.current_index != -1) {
        
        this.current_index = -1;

        var li_element = null;
        for (var i = 0, l = this.li_array.length; i < l; i++) {
          li_element = this.li_array[i];
          li_element.removeClass('open-version');
        }
      }

    },

    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          
    on_question_element_clicked: function(event) {
      event.preventDefault();

      var target = $(event.currentTarget);
      var index = target.data('index');

      console.log("this.current_index: " + this.current_index)
      console.log("index: " + index);

      if(index == this.current_index){
        this.close_all();
      } else {
        this.open_index(index);
      }

    }

  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_faq_section'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_faq_section')) {
        $.data(this, 'gryphon_faq_section', new GryphonFAQSection(this, settings));
      }
    });
  };

}(jQuery));