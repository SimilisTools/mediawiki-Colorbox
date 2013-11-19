$(document).ready( function() {

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
