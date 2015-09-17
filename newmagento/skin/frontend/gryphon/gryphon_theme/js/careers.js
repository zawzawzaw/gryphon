jQuery(document).ready(function($) {
  jQuery(".phone_no_input, .mobile_no_input").intlTelInput({
    defaultCountry: 'sg',
    nationalMode: false,
    autoHideDialCode: false,
    autoPlaceholder: false
  });

  
  jQuery("#resume-file-upload #resume").change(function(){
    var resume_filename = jQuery("#resume-file-upload #resume").val();
    jQuery("#resume-file-upload .filename").html(resume_filename);
  });

  jQuery('#career-container').gryphon_careers({});

  // home page carousel
  $('.careers-slick').slick({
      dots: false,
      infinite: false,
      speed: 300,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
          {
              breakpoint: 1099,
              settings: {
                  slidesToShow: 4,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: false
              }
          },
          {
              breakpoint: 960,
              settings: {
                  slidesToShow: 3,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: false
              }
          },
          {
              breakpoint: 760,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: false
              }
          },
          {
              breakpoint: 600,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  infinite: true,
                  dots: false
              }
          }                      
      ]
  });  

});