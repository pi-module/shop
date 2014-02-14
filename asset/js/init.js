(function($){
	$(function(){

    $("li.category").click(function() {
      if (!$(this).hasClass("hidden")) {
        $(this).siblings().addClass("hidden");
        $(this).addClass("current");
        var index = $(this).index();
        var offset = (-1 * $(this).height()) * index;
        $(this).animate({ "top": offset  }, 400 );        
        $("#" + $(this).data("target")).fadeIn( 500 );
      }
    });
    
    $(".back-btn").click(function() {
      $(".menu li").removeClass().css({ "top": "auto" });
      $(".sub-menu").hide();
    });
    
	
	}); // end of document ready
})(jQuery); // end of jQuery name space