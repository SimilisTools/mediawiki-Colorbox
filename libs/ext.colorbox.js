$(windows).load( function() {

	if ( $("#colorboxload").length > 0 ) {
	
		// It exists. So process
		var target = $("#colorboxload").attr('data-page');
		var target_class = $("#colorboxload").attr('data-class');
		
		
		$.get( mw.util.wikiScript(), {
			format: 'json',
			action: 'ajax',
			rs: 'Colorbox::getPageContent',
			rsargs: [target] // becomes &rsargs[]=arg1&rsargs[]=arg2...
		}, function(data) {
			var output = "<div class='"+target_class+"'>"+data+"</div>";
			$.colorbox({html:output});
		});
		
	}

	/** Go through all colorboximg **/
	$(".colorboximg").each( function( i ) {
	
		console.log(this);
		$(this).find("a.image").each( function( a ){
			
			var link = this;
			// var href = $(this).attr('href');
			var imgpre = $(this).children("img")[0].attr("src");
			var img = imgpre;
			
			// We do something if thumb
			var patt = new RegExp("/thumb/");
			if ( patt.test( img ) ) {
				img = imgpre.replace( "/thumb", "" );
				var imgparts = img.split("/");
				imgparts.pop();
				img = imgparts.join("/");
			}
			
			$(link).attr("href", img);
		});
	
	});
	
});


// On click on Colorbox link
$(".colorboxlink").click( function() {

	var target = $(this).attr('data-page');
	var target_class = $(this).attr('data-class');
	
	$.get( mw.util.wikiScript(), {
		format: 'json',
		action: 'ajax',
		rs: 'Colorbox::getPageContent',
		rsargs: [target] // becomes &rsargs[]=arg1&rsargs[]=arg2...
	}, function(data) {
		var output = "<div class='"+target_class+"'>"+data+"</div>";
		$.colorbox({html:output});
	});

});
