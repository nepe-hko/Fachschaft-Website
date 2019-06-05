
jQuery(document).ready(function() {
  jQuery(".event").click(function(event) {
    var url  = window.location.href;
    jQuery(url+'#' + event.target.id + '_scrollPos').get(0).scrollIntoView();
  });
});
