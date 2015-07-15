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
});