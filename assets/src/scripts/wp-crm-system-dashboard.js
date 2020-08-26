jQuery(window).on("load", function(){
  jQuery('.wpcrm-dashboard').each(function() {
    // Get height of each .wpcrm-dashboard div
    var divh = jQuery(this).height();
    // if divh > 315 the default height, set height of .wpcrm-dashboard to divh
    if (divh > "315") {
      jQuery(".wpcrm-dashboard").css('height', divh +"px" );
    }
  });
});