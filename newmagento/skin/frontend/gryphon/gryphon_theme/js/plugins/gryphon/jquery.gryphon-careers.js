/* Nano Templates (Tomasz Mazur, Jacek Becela) */

function nano(template, data) {
  return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
    var keys = key.split("."), v = data[keys.shift()];
    for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
    return (typeof v !== "undefined" && v !== null) ? v : "";
  });
}





/**
 * ...
 * @author Jairus
 */

(function ($) {

  var defaults = {
    single_version_html: [
      '<div class="career-display-item-single">',
        '<div class="row">',
          '<div class="col-md-2"><div class="space10"></div></div>',
          '<div class="col-md-8">',
            '<h4>{title}</h4>',
            '<h5>{workhours}</h5>',
            '<p>{description}</p>',
          '</div>',
        '</div>',
        '<div class="row">',
          '<div class="col-md-1"><div class="space10"></div></div>',
          '<div class="col-md-5">',
            '<h6>responsibilities</h6>',
            '<ul class="responsibilities"></ul>',
          '</div>',
          '<div class="col-md-5">',
            '<h6>requirements</h6>',
            '<ul class="requirements"></ul>',
          '</div>',
        '</div>',
      '</div>'
    ].join(""),

    multiple_version_html: [
      '<div class="career-display-item">',
        '<h5>{workhours}</h5>',
        '<h4>{title}</h4>',
        '<div class="detail-button">See Detail</div>',
      '</div>'
    ].join("")

  };

  //////////////////////////
  // GryphonCareers Class //
  //////////////////////////

  function GryphonCareers(elem, settings) {
    this.element = $(elem);
    this.settings = $.extend({}, defaults, settings);

    this.data_array = [];

    this.display_container = this.element.find('#career-display-item-container');
    this.fullscreen_container = this.element.find('#careers-fullscreen-display');

    this.fullscreen_career_item = this.element.find('#careers-fullscreen-display .career-display-item-single');

    this.current_multiple_index = -1;
    this.max_multiple_index = 0;

    this.init();
  }

  GryphonCareers.prototype = {
    init: function () {
      console.log("init");

      this.parse_data_html();
      this.create_html();


      this.fullscreen_container.find('.black-bg').click(this.on_black_bg_click.bind(this));
      this.fullscreen_container.find('.apply-button').click(this.on_fullscreen_apply_button_click.bind(this));
      this.fullscreen_container.find('.next-button').click(this.on_next_button_click.bind(this));
      this.fullscreen_container.find('.prev-button').click(this.on_prev_button_click.bind(this));



    },
    parse_data_html: function(){

      var arr = this.element.find('#careers-item-data .career-item');
      var item_element = null;
      var data_object = null;

      var job_title = "";
      var job_workhours = "";
      var job_description = "";
      var job_responsibilities = [];
      var job_requirements = [];

      var arr_01 = null;
      var arr_02 = null;


      for (var i = 0, l = arr.length; i < l; i++) {
        item_element = $(arr[i]);

        job_title = "" + item_element.find(".title").html();
        job_workhours = "" + item_element.find(".workhours").html();
        job_description = "" + item_element.find(".description").html();
        job_responsibilities = [];
        job_requirements = [];

        arr_01 = item_element.find("ul.responsibilities li");
        arr_02 = item_element.find("ul.requirements li");

        for (var ii = 0, ll = arr_01.length; ii < ll; ii++) {
          job_responsibilities[ii] = $(arr_01[ii]).html();
        }

        for (var iii = 0, lll = arr_02.length; iii < lll; iii++) {
          job_requirements[iii] = $(arr_02[iii]).html();
        }


        data_object = {
          title: job_title,
          workhours: job_workhours,
          description: job_description,
          responsibilities: job_responsibilities,
          requirements: job_requirements
        };

        this.data_array[i] = data_object;

      } // end for


      this.max_multiple_index = this.data_array.length - 1;

      console.log('Gryphon Careers Data: ');
      console.log(this.data_array);

    },

    create_html: function(){

      if(this.data_array.length == 0) {
        // no nothing
        
      } else if(this.data_array.length == 1){
        this.create_single_career_item();

      } else if(this.data_array.length > 1){
        this.create_multiple_career_items();
      }
      

      
    },

    create_single_career_item: function(){
      console.log('create_single_career_item');

      var data_object = this.data_array[0];
      var single_str = nano(this.settings['single_version_html'],data_object)
      var single_element = $(single_str);

      var responsibilities_str = "<li>" + data_object.responsibilities.join('</li><li>') + "</li>";
      var requirements_str = "<li>" + data_object.requirements.join('</li><li>') + "</li>";

      single_element.find('.responsibilities').html(responsibilities_str);
      single_element.find('.requirements').html(requirements_str);

      this.display_container.empty();
      this.display_container.append(single_element);
      
    }, 

    create_multiple_career_items: function(){
      console.log('create_multiple_career_items');

      var data_object = null;
      var career_item_str = "";
      var career_item_element = null;


      var multiple_str_template = this.settings['multiple_version_html'];

      var fragment = $(document.createDocumentFragment());


      for (var i = 0, l = this.data_array.length; i < l; i++) {

        data_object = this.data_array[i];
        career_item_str = nano(multiple_str_template, data_object);
        career_item_element = $(career_item_str);

        career_item_element.data('index', i);
        career_item_element.click(this.on_multiple_career_item_click.bind(this));

        fragment.append(career_item_element);
      }


      this.display_container.empty();
      this.display_container.append(fragment);
    },

    display_multiple_item_detail: function(index_param){

      if(this.data_array[index_param] != null && this.data_array[index_param] != undefined){
        if(this.current_multiple_index != index_param){

          this.current_multiple_index = index_param;

          var data_object = this.data_array[this.current_multiple_index];

          this.fullscreen_career_item.find('h4').html( "" + data_object.title );
          this.fullscreen_career_item.find('h5').html( "" + data_object.workhours);
          this.fullscreen_career_item.find('p').html( "" + data_object.description);

          var responsibilities_str = "<li>" + data_object.responsibilities.join('</li><li>') + "</li>";
          var requirements_str = "<li>" + data_object.requirements.join('</li><li>') + "</li>";          

          this.fullscreen_career_item.find('.responsibilities-col ul').empty();
          this.fullscreen_career_item.find('.responsibilities-col ul').html(responsibilities_str);

          this.fullscreen_career_item.find('.requirements-col ul').empty();
          this.fullscreen_career_item.find('.requirements-col ul').html(requirements_str);


          if (this.is_mobile()) {
            var target_y = this.fullscreen_container.offset().top;
            target_y -= 60;
            target_y -= 30;
            var current_scroll = $(window).scrollTop();
            var target_duration = Math.abs(  (target_y - current_scroll) / 800 );
            TweenMax.to($(window), target_duration, {scrollTo:{y:target_y,autoKill: true}, ease:Quad.easeInOut});
          }


        }
      }
    },


    show_fullscreen_container: function(){
      this.fullscreen_container.show(0);

    },
    hide_fullscreen_container: function(){
      this.fullscreen_container.hide(0);

    },

    is_mobile: function(){
      return ($(window).width() < 922 );
    },


    //    _______     _______ _   _ _____ ____  
    //   | ____\ \   / / ____| \ | |_   _/ ___| 
    //   |  _|  \ \ / /|  _| |  \| | | | \___ \ 
    //   | |___  \ V / | |___| |\  | | |  ___) |
    //   |_____|  \_/  |_____|_| \_| |_| |____/ 
    //                                          

    on_multiple_career_item_click: function(event){

      event.preventDefault();
      var target = $(event.currentTarget);
      var index = target.data('index');

      console.log('on_multiple_career_item_click: ' + index);

      this.show_fullscreen_container();
      this.display_multiple_item_detail(index);

    },

    on_black_bg_click: function(event){
      event.preventDefault();
      this.hide_fullscreen_container();
    },
    on_fullscreen_apply_button_click: function(event){
      event.preventDefault();
      this.hide_fullscreen_container();
            
      var target_y = $('.application-form-banner').offset().top - 100 - 45;
      var current_scroll = $(window).scrollTop();
      var target_duration = Math.abs(  (target_y - current_scroll) / 800 );
      TweenMax.to($(window), target_duration, {scrollTo:{y:target_y,autoKill: true}, ease:Quad.easeInOut});

      var data_object = this.data_array[this.current_multiple_index];
      var subject_str = 'Application: ' + data_object.title;
      $('#application-form #subject').val(subject_str);
      

    },
    on_next_button_click: function(event){
      event.preventDefault();

      var target_index = this.current_multiple_index + 1;
      target_index = target_index >= this.max_multiple_index ? this.max_multiple_index : target_index;

      this.display_multiple_item_detail(target_index);

    },
    on_prev_button_click: function(event){
      event.preventDefault();

      var target_index = this.current_multiple_index - 1;
      target_index = target_index < 0 ? 0 : target_index;

      this.display_multiple_item_detail(target_index);

      

    }

  };

  ////////////////////////
  // jQuery Plugin Code //
  ////////////////////////

  $.fn['gryphon_careers'] = function (settings) {
    return this.each(function () {
      // check for instance of plugin in object
      if (!$.data(this, 'gryphon_careers')) {
        $.data(this, 'gryphon_careers', new GryphonCareers(this, settings));
      }
    });
  };

}(jQuery));