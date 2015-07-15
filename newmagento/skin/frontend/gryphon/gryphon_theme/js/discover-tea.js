jQuery(document).ready(function($) {
  var skinUrl = getSkinUrl();
  var jsonUrl = skinUrl+"frontend/gryphon/gryphon_theme/json/discover-tea-2.json";
  console.log(jsonUrl);
  jQuery('#gryphon-discover-tea-content').gryphon_discover_tea({
    json: jsonUrl
  });
});