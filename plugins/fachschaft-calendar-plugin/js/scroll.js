jQuery(document).ready(function() {
jQuery(".event").click(function(event) {
  var url  = window.location.href;
  // jQuery('#' + event.target.id + '_scrollPos').get(0).scrollIntoView();

  var pos = jQuery('#' + event.target.id + '_scrollPos').position();
  jQuery(window).scrollTop(pos.top);
});
});

jQuery(document).ready(function() {
  jQuery( ".fachschaft_calendar_plugin_widget" ).bind('click', function() {

    jQuery(location).attr('href','http://localhost/wordpress/veranstaltungen');
  });
});
