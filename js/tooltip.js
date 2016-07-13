jQuery(function() {
	jQuery( document ).tooltip({
	  position: {
		my: "center bottom-20",
		at: "center top",
		using: function( position, feedback ) {
		  jQuery( this ).css( position );
		  jQuery( "<div>" )
			.addClass( "arrow" )
			.addClass( feedback.vertical )
			.addClass( feedback.horizontal )
			.appendTo( this );
		}
	  }
	});
});