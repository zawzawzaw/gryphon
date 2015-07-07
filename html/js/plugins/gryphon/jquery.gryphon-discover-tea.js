/* Nano Templates (Tomasz Mazur, Jacek Becela) */

function nano(template, data) {
  return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
    var keys = key.split("."), v = data[keys.shift()];
    for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
    return (typeof v !== "undefined" && v !== null) ? v : "";
  });
}



// https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/Array/indexOf

// Production steps of ECMA-262, Edition 5, 15.4.4.14
// Reference: http://es5.github.io/#x15.4.4.14
if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function(searchElement, fromIndex) {

    var k;

    // 1. Let O be the result of calling ToObject passing
    //    the this value as the argument.
    if (this == null) {
      throw new TypeError('"this" is null or not defined');
    }

    var O = Object(this);

    // 2. Let lenValue be the result of calling the Get
    //    internal method of O with the argument "length".
    // 3. Let len be ToUint32(lenValue).
    var len = O.length >>> 0;

    // 4. If len is 0, return -1.
    if (len === 0) {
      return -1;
    }

    // 5. If argument fromIndex was passed let n be
    //    ToInteger(fromIndex); else let n be 0.
    var n = +fromIndex || 0;

    if (Math.abs(n) === Infinity) {
      n = 0;
    }

    // 6. If n >= len, return -1.
    if (n >= len) {
      return -1;
    }

    // 7. If n >= 0, then Let k be n.
    // 8. Else, n<0, Let k be len - abs(n).
    //    If k is less than 0, then let k be 0.
    k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

    // 9. Repeat, while k < len
    while (k < len) {
      // a. Let Pk be ToString(k).
      //   This is implicit for LHS operands of the in operator
      // b. Let kPresent be the result of calling the
      //    HasProperty internal method of O with argument Pk.
      //   This step can be combined with c
      // c. If kPresent is true, then
      //    i.  Let elementK be the result of calling the Get
      //        internal method of O with the argument ToString(k).
      //   ii.  Let same be the result of applying the
      //        Strict Equality Comparison Algorithm to
      //        searchElement and elementK.
      //  iii.  If same is true, return k.
      if (k in O && O[k] === searchElement) {
        return k;
      }
      k++;
    }
    return -1;
  };
}



/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
    json: ""
  };

  //////////////////////////////
  // GryphonDiscoverTea Class //
  //////////////////////////////

  function GryphonDiscoverTea(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.data_array = [];

    this.question_01_button_array = [];
    this.question_02_button_array = [];
    this.question_03_button_array = [];
    this.question_04_button_array = [];
    this.question_05_button_array = [];

    this.question_01_buttons = null;
    this.question_02_buttons = null;
    this.question_03_buttons = null;
    this.question_04_buttons = null;
    this.question_05_buttons = null;

    this.is_question_01_disabled = false;
    this.is_question_02_disabled = false;
    this.is_question_03_disabled = false;
    this.is_question_04_disabled = false;
    this.is_question_05_disabled = false;

    this.question_01_answer = "none";
    this.question_02_answer = [];
    this.question_03_answer = "none";
    this.question_04_answer = [];
    this.question_05_answer = "none";

    this.result_01_element = null;
    this.result_02_element = null;
    this.result_03_element = null;
    this.result_04_element = null;


    this.question_01_element = this.element.find("#discover-tea-question-01");
    this.question_02_element = this.element.find("#discover-tea-question-02");
    this.question_03_element = this.element.find("#discover-tea-question-03");
    this.question_04_element = this.element.find("#discover-tea-question-04");
    this.question_05_element = this.element.find("#discover-tea-question-05");

    this.question_02_gift_element = this.element.find("#discover-tea-question-02-gift");

    this.result_question_element = this.element.find("#discover-tea-result .results-item-top");
    this.result_container_element = this.element.find("#discover-tea-result #results-item-container");

    

    this.get_results_button = this.element.find("#get-results-button");
    this.get_results_button.click(this.on_get_results_button_click.bind(this));

    // restart button click.
    this.element.find(".discover-restart-button").click(this.on_restart_button_click.bind(this));

    this.init();
  }

  GryphonDiscoverTea.prototype = {
    init: function () {
      console.log("init");

      this.create_button();
      this.load_json();

    },
    create_button: function(){

      var button_element = null;

      // question 01
      this.question_01_buttons = this.question_01_element.find('.for-who');
      for (var i = 0, l = this.question_01_buttons.length; i < l; i++) {
        button_element = $(this.question_01_buttons[i]);
        button_element.click(this.on_question_01_click.bind(this));
        this.question_01_button_array[i] = button_element;
      }

      // question 02
      this.question_02_buttons = this.question_02_element.find('.tea-category');

      for (var i = 0, l = this.question_02_buttons.length; i < l; i++) {
        button_element = $(this.question_02_buttons[i]);
        button_element.click(this.on_question_02_click.bind(this));
        this.question_02_button_array[i] = button_element;
      }

      // question 03
      this.question_03_buttons = this.question_03_element.find('.tea-type');
      for (var i = 0, l = this.question_03_buttons.length; i < l; i++) {
        button_element = $(this.question_03_buttons[i]);
        button_element.click(this.on_question_03_click.bind(this));
        this.question_03_button_array[i] = button_element;
      }

      // question 04
      this.question_04_buttons = this.question_04_element.find('.tea-aroma');

      for (var i = 0, l = this.question_04_buttons.length; i < l; i++) {
        button_element = $(this.question_04_buttons[i]);
        button_element.click(this.on_question_04_click.bind(this));
        this.question_04_button_array[i] = button_element;
      }


      // question 05
      this.question_05_buttons = this.question_05_element.find('.how-start');
      for (var i = 0, l = this.question_05_buttons.length; i < l; i++) {
        button_element = $(this.question_05_buttons[i]);
        button_element.click(this.on_question_05_click.bind(this));
        this.question_05_button_array[i] = button_element;
      }
      
    },


    load_json: function(){

      if(this.settings['json'] != ""){

        $.ajax({
          type: 'GET',
          data: {},
          url: this.settings['json'],
          complete: this.on_json_load_complete.bind(this)
        });
      } else {
        console.log("json is not set")
      }


    },

    create_results: function(){

      this.is_question_01_disabled = true;
      this.is_question_02_disabled = true;
      this.is_question_03_disabled = true;
      this.is_question_04_disabled = true;
      this.is_question_05_disabled = true;

      console.log("this.question_01_answer: " + this.question_01_answer);
      console.log("this.question_02_answer: " + this.question_02_answer);
      console.log("this.question_03_answer: " + this.question_03_answer);
      console.log("this.question_04_answer: " + this.question_04_answer);
      console.log("this.question_05_answer: " + this.question_05_answer);
      //this.data_array.length
      
      this.question_01_buttons.addClass('disabled');
      this.question_02_buttons.addClass('disabled');
      this.question_03_buttons.addClass('disabled');
      this.question_04_buttons.addClass('disabled');
      this.question_05_buttons.addClass('disabled');

      this.question_01_buttons.not(".selected").addClass('not-selected');
      this.question_02_buttons.not(".selected").addClass('not-selected');
      this.question_03_buttons.not(".selected").addClass('not-selected');
      this.question_04_buttons.not(".selected").addClass('not-selected');
      this.question_05_buttons.not(".selected").addClass('not-selected');

      

      

      var result_01_array = [];
      var result_02_array = [];
      var result_03_array = [];

      var data_object = null;
      // no filter for question 1
      
      var category_index = 0;
      var aroma_index = 0;
      var aroma_index_inner = 0;
      var data_aroma_array = [];


      // filter 02
      for (var i = 0, l = this.data_array.length; i < l; i++) {
        data_object = this.data_array[i];

        category_index = -1;
        category_index = this.question_02_answer.indexOf(data_object.category);

        if (category_index != -1) {
          result_01_array[result_01_array.length] = data_object;
        }
      }

      // filter 03
      for (var i = 0, l = result_01_array.length; i < l; i++) {
        data_object = result_01_array[i];

        if (data_object.type == this.question_03_answer) {
          result_02_array[result_02_array.length] = data_object;
        }
      }

      console.log('filter 04')
      // filter 04
      for (var i = 0, l = result_02_array.length; i < l; i++) {
        data_object = result_02_array[i];
        data_aroma_array = data_object.aroma;

        aroma_index = -1;

        for (var j = 0, jl = data_aroma_array.length ; j < jl ; j++) {

          aroma_index_inner = this.question_04_answer.indexOf(data_aroma_array[j]);

          if(aroma_index_inner != -1){
            aroma_index = j;
          }
        }

        //console.log('data_object.aroma: ' + data_object.aroma)

        aroma_index = this.question_04_answer.indexOf(data_object.aroma);

        if (aroma_index != -1) {
          result_03_array[result_03_array.length] = data_object;
        }
      }


      console.log('result_01_array');
      console.log(result_01_array);
      console.log('result_02_array');
      console.log(result_02_array);
      console.log('result_03_array');
      console.log(result_03_array);


      //    ____  _   _  _____        __  ____  _____ ____  _   _ _   _____ ____  
      //   / ___|| | | |/ _ \ \      / / |  _ \| ____/ ___|| | | | | |_   _/ ___| 
      //   \___ \| |_| | | | \ \ /\ / /  | |_) |  _| \___ \| | | | |   | | \___ \ 
      //    ___) |  _  | |_| |\ V  V /   |  _ <| |___ ___) | |_| | |___| |  ___) |
      //   |____/|_| |_|\___/  \_/\_/    |_| \_\_____|____/ \___/|_____|_| |____/ 
      //                                                                          
      
      /*
      
      var temp_template = [
        '<div class="results-item col-md-2">',
          '<h4>{name}</h4>',
          '<p>category: {category}</p>',
          '<p>type: {type}</p>',
          '<p>aroma: {aroma}</p>',
        '</div>'
      ].join("");
      
      var str = "";
      str += '<div class="col-md-12"><hr class="big"><h1>After Category Filter</h1><hr class="big"></div>';

      for (var i = 0; i < result_01_array.length; i++) {
        var item_str = nano(temp_template,result_01_array[i]);
        str += item_str;
      }

      str += '<div class="col-md-12"><hr class="big"><h1>After Type Filter</h1> <hr class="big"></div>';
      for (var i = 0; i < result_02_array.length; i++) {
        var item_str = nano(temp_template,result_02_array[i]);
        str += item_str;
      }

      str += '<div class="col-md-12"><hr class="big"><h1>After Aroma Filter</h1> <hr class="big"></div>';
      
      for (var i = 0; i < result_03_array.length; i++) {
        var item_str = nano(temp_template,result_03_array[i]);
        str += item_str;
      }

      if(result_03_array.length == 0){
        str += '<div class="col-md-2">no results</div>';
      }

      this.result_container_element.append(str);
      */
      

      var item_template = [
        '<div class="col-md-3">',
          '<div class="results-item" data-id="{id}" data-sku="{sku}">',
            '<div class="results-item-image">',
              '<a href="{url}" target="_blank"></a>',
            '</div>',
            '<a href="{url}" target="_blank"><h4>{name}</h4></a>',
            '<p>{category}</p>',
            '<div class="buttons">',
              '<div class="zoom"></div>',
              '<div class="add"></div>',
            '</div>',
          '</div>',
        '</div>'
      ].join('');





      var final_results = [];
      for (var i = 0, l=result_03_array.length; i < l; i++) {
        final_results[final_results.length] = result_03_array[i];
      }

      final_results = this.shuffle(final_results);

      if(final_results.length < 4){
        var original_result_length = final_results.length;
        for (var i = 0, l=result_02_array.length; i < l; i++) {
          final_results[final_results.length] = result_02_array[i];
        }

        if(original_result_length == 1 || original_result_length == 0){
          final_results = this.shuffle(final_results);
        }

        final_results.splice(4,1000);
      }

      if(final_results.length < 4){
        for (var i = 0, l=result_01_array.length; i < l; i++) {
          final_results[final_results.length] = result_01_array[i];
          final_results = this.shuffle(final_results);
        }

        final_results.splice(4,1000);
      }

      console.log('final_results:');
      console.log(final_results);

      var results_str_array = [];
      

      for (var i = 0, l = final_results.length; i < l; i++) {
        results_str_array[results_str_array.length] = nano(item_template, final_results[i]);
      }

      results_str = results_str_array.join("");

      this.result_container_element.find('.row').append(results_str);

      var result_element_array = this.result_container_element.find(".results-item");

      this.result_01_element = $(result_element_array[0]);
      this.result_02_element = $(result_element_array[1]);
      this.result_03_element = $(result_element_array[2]);
      this.result_04_element = $(result_element_array[3]);


      $.ajax({
        type: 'GET',
        data: {},
        url: 'http://test.gryphontea.com/magento/discovertea/index?id=' + final_results[0].id,
        complete: this.on_result_01_complete.bind(this)
      });

      $.ajax({
        type: 'GET',
        data: {},
        url: 'http://test.gryphontea.com/magento/discovertea/index?id=' + final_results[1].id,
        complete: this.on_result_02_complete.bind(this)
      });

      $.ajax({
        type: 'GET',
        data: {},
        url: 'http://test.gryphontea.com/magento/discovertea/index?id=' + final_results[2].id,
        complete: this.on_result_03_complete.bind(this)
      });

      $.ajax({
        type: 'GET',
        data: {},
        url: 'http://test.gryphontea.com/magento/discovertea/index?id=' + final_results[3].id,
        complete: this.on_result_04_complete.bind(this)
      });

    },

    reset_questions: function() {

      this.is_question_01_disabled = false;
      this.is_question_02_disabled = false;
      this.is_question_03_disabled = false;
      this.is_question_04_disabled = false;
      this.is_question_05_disabled = false;

      this.question_01_answer = "none";
      this.question_02_answer = [];
      this.question_03_answer = "none";
      this.question_04_answer = [];
      this.question_05_answer = "none";


      this.question_01_buttons.removeClass('selected not-selected disabled');
      this.question_02_buttons.removeClass('selected not-selected disabled');
      this.question_03_buttons.removeClass('selected not-selected disabled');
      this.question_04_buttons.removeClass('selected not-selected disabled');
      this.question_05_buttons.removeClass('selected not-selected disabled');


      this.question_02_element.slideUp(500);
      this.question_03_element.slideUp(500);
      this.question_04_element.slideUp(500);
      this.question_05_element.slideUp(500);

      this.question_02_gift_element.slideUp(500);

      this.result_container_element.slideUp(500);


      var header_height = $("#header-wrapper").height();
      var target_y = this.question_01_element.offset().top - header_height;

      TweenMax.delayedCall(0.6, this.scroll_to, [target_y], this);
      //this.scroll_to(target_y);


    },



    //    ____ ___ ____  ____  _        _ __   __     __     _    _   _ ___ __  __    _  _____ ___ ___  _   _ 
    //   |  _ \_ _/ ___||  _ \| |      / \\ \ / /    / /    / \  | \ | |_ _|  \/  |  / \|_   _|_ _/ _ \| \ | |
    //   | | | | |\___ \| |_) | |     / _ \\ V /    / /    / _ \ |  \| || || |\/| | / _ \ | |  | | | | |  \| |
    //   | |_| | | ___) |  __/| |___ / ___ \| |    / /    / ___ \| |\  || || |  | |/ ___ \| |  | | |_| | |\  |
    //   |____/___|____/|_|   |_____/_/   \_\_|   /_/    /_/   \_\_| \_|___|_|  |_/_/   \_\_| |___\___/|_| \_|
    //                                                                                                        


    display_question_02: function(){
      this.question_02_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 45;
      var target_y = this.question_02_element.offset().top - header_height - padding_top;

      console.log("target_y: " + target_y);
      this.scroll_to(target_y);
    },
    display_question_02_gift: function(){
      this.question_02_gift_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 45;
      var target_y = this.question_02_gift_element.offset().top - header_height - padding_top;

      console.log("target_y: " + target_y);
      this.scroll_to(target_y);
    },
    
    display_question_03: function(){
      this.question_03_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 45;
      var target_y = this.question_03_element.offset().top - header_height - padding_top;
      this.scroll_to(target_y);
    },
    display_question_04: function(){
      this.question_04_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 45;
      var target_y = this.question_04_element.offset().top - header_height - padding_top;
      this.scroll_to(target_y);
    },
    display_question_05: function(){
      this.question_05_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 45 + 200;
      var target_y = this.question_05_element.offset().top - header_height - padding_top;
      this.scroll_to(target_y);
    },
    display_results_question: function(){
      this.result_question_element.slideDown(500);

      var header_height = $("#header-wrapper").height();
      var padding_top = 0;
      var target_y = this.question_05_element.offset().top - header_height - padding_top;
      this.scroll_to(target_y);
    },
    display_results_container: function(){
      this.result_container_element.slideDown(500);
    },

    hide_results_question: function(){
      this.result_question_element.slideUp(500);
    },


    //       _    ____ ___   _______     _______ _   _ _____ ____  
    //      / \  |  _ \_ _| | ____\ \   / / ____| \ | |_   _/ ___| 
    //     / _ \ | |_) | |  |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //    / ___ \|  __/| |  | |___  \ V / | |___| |\  | | |  ___) |
    //   /_/   \_\_|  |___| |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                                             

    on_result_01_complete: function(event){
      var data = JSON.parse(event['responseText']);
      var image_str = '<img src="' + 'http://test.gryphontea.com/magento/media/catalog/product' + data.image + '">'
      this.result_01_element.find('.results-item-image a').append(image_str);
    },
    on_result_02_complete: function(event){
      var data = JSON.parse(event['responseText']);
      var image_str = '<img src="' + 'http://test.gryphontea.com/magento/media/catalog/product' + data.image + '">'
      this.result_02_element.find('.results-item-image a').append(image_str);
    },
    on_result_03_complete: function(event){
      var data = JSON.parse(event['responseText']);
      var image_str = '<img src="' + 'http://test.gryphontea.com/magento/media/catalog/product' + data.image + '">'
      this.result_03_element.find('.results-item-image a').append(image_str);
    },
    on_result_04_complete: function(event){
      var data = JSON.parse(event['responseText']);
      var image_str = '<img src="' + 'http://test.gryphontea.com/magento/media/catalog/product' + data.image + '">'
      this.result_04_element.find('.results-item-image a').append(image_str);
    },

    //        _ ____   ___  _   _   _______     _______ _   _ _____ ____  
    //       | / ___| / _ \| \ | | | ____\ \   / / ____| \ | |_   _/ ___| 
    //    _  | \___ \| | | |  \| | |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |_| |___) | |_| | |\  | | |___  \ V / | |___| |\  | | |  ___) |
    //    \___/|____/ \___/|_| \_| |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                                                    

    on_json_load_complete: function(event){
      this.data_array = JSON.parse(event['responseText']);

      console.log('on_json_load_complete');
      console.log(this.data_array);
    },

    //    __  __  ___  _   _ ____  _____   _______     _______ _   _ _____ ____  
    //   |  \/  |/ _ \| | | / ___|| ____| | ____\ \   / / ____| \ | |_   _/ ___| 
    //   | |\/| | | | | | | \___ \|  _|   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |  | | |_| | |_| |___) | |___  | |___  \ V / | |___| |\  | | |  ___) |
    //   |_|  |_|\___/ \___/|____/|_____| |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                                                           

    on_question_01_click: function(event){
      event.preventDefault();

      if (this.is_question_01_disabled == false) {
        var target = $(event.currentTarget);
        this.question_01_answer = target.attr('data-for-who');

        console.log('Question 01: ' + this.question_01_answer);

        this.question_01_buttons.addClass('disabled not-selected');
        target.removeClass('not-selected').addClass('selected');

        this.is_question_01_disabled = true;

        if( this.question_01_answer == "others" ){
          this.display_question_02_gift();
        } else {
          this.display_question_02();

          //this.display_question_03();
          //this.display_question_04();
          //this.display_question_05();
        }

      }
    },

    on_question_02_click: function(event){
      event.preventDefault();

      if (this.is_question_02_disabled == false) {
        var target = $(event.currentTarget);
        var answer = target.attr('data-category');
        var answer_index = this.question_02_answer.indexOf(answer);

        if (answer_index == -1) {
        
          

          if(this.question_02_answer.length <= 2){
            target.addClass('selected');
            this.question_02_answer[this.question_02_answer.length] = answer;
          }

          TweenMax.killDelayedCallsTo(this.display_question_03);
          TweenMax.delayedCall(2, this.display_question_03, [], this);

          //this.display_question_03();
          
          /*
          
          select answer  
          this.question_02_answer[this.question_02_answer.length] = answer;

          target.addClass('selected');

          if(this.question_02_answer.length == 3){

            console.log('Question 02: ' + this.question_02_answer);

            this.question_02_buttons.addClass('disabled');
            this.question_02_buttons.not('.selected').addClass('not-selected');

            this.is_question_02_disabled = true;
            // this.display_question_03();

          }
          */


        } else {
          // deselect answer
          this.question_02_answer.splice(answer_index);
          target.removeClass('selected');
        }



      }

    },

    on_question_03_click: function(event){
      event.preventDefault();

      if (this.is_question_03_disabled == false) {
        var target = $(event.currentTarget);
        this.question_03_answer = target.attr('data-type');

        console.log('Question 03: ' + this.question_03_answer);

        //this.question_03_buttons.addClass('disabled not-selected');
        this.question_03_buttons.addClass('not-selected');
        target.removeClass('not-selected').addClass('selected');

        //this.is_question_03_disabled = true;
        this.display_question_04();
      }

    },

    on_question_04_click: function(event){
      event.preventDefault();

      if (this.is_question_04_disabled == false) {
        var target = $(event.currentTarget);
        var answer = target.attr('data-aroma');
        var answer_index = this.question_04_answer.indexOf(answer);

        if (answer_index == -1) {

          if(this.question_04_answer.length <= 2){
            target.addClass('selected');
            this.question_04_answer[this.question_04_answer.length] = answer;
          }
        
          /*
          // select answer  
          this.question_04_answer[this.question_04_answer.length] = answer;

          target.addClass('selected');

          if(this.question_04_answer.length == 3){

            console.log('Question 04: ' + this.question_04_answer);

            this.question_04_buttons.addClass('disabled');
            this.question_04_buttons.not('.selected').addClass('not-selected');

            this.is_question_04_disabled = true;
            this.display_question_05();

          }
          */
         
          //this.display_question_05();
          TweenMax.killDelayedCallsTo(this.display_question_05);
          TweenMax.delayedCall(2, this.display_question_05, [], this);

        } else {
          // deselect answer
          this.question_04_answer.splice(answer_index);
          target.removeClass('selected');
        }



      }
    },


    on_question_05_click: function(event) {
      event.preventDefault();

      if (this.is_question_05_disabled == false) {
        var target = $(event.currentTarget);
        this.question_05_answer = target.attr('data-how-start');

        console.log('Question 05: ' + this.question_05_answer);

        this.question_05_buttons.addClass('disabled not-selected');
        target.removeClass('not-selected').addClass('selected');

        this.is_question_05_disabled = true;
        this.display_results_question();
      }
    },


    on_get_results_button_click: function(event) {
      event.preventDefault();

      this.hide_results_question();

      this.create_results();
      this.display_results_container();
    },

    on_restart_button_click: function(event){
      event.preventDefault();

      this.reset_questions();

    },

    //    _   _ _____ ___ _     
    //   | | | |_   _|_ _| |    
    //   | | | | | |  | || |    
    //   | |_| | | |  | || |___ 
    //    \___/  |_| |___|_____|
    //                          

    shuffle: function(array) {
      var currentIndex = array.length, temporaryValue, randomIndex;
      // While there remain elements to shuffle...
      while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }

      return array;


    },
    scroll_to: function(num_param){

      var target_y = num_param
      var current_scroll = $(window).scrollTop();
      var target_duration = Math.abs(  (target_y - current_scroll) / 800 );
      TweenMax.to($(window), target_duration, {scrollTo:{y:target_y,autoKill: true}, ease:Quad.easeInOut});

    }


  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_discover_tea'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_discover_tea')) {
        $.data(this, 'gryphon_discover_tea', new GryphonDiscoverTea(this, settings));
      }
    });
  };

}(jQuery));