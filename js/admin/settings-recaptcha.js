(function($){
	var links = '.nav-tab-wrapper a[href^="#"]';
	$(document).on( 'click', links, function(e){
		var selector = $(this).attr('href');
		$('.nav-tab-content').each(function(i,el){
			$(this).toggleClass('active', $(this).is( selector ));
		});
		$( links ).each(function(){
			$(this).toggleClass('nav-tab-active', $(this).attr('href') === selector );
		});
		e.preventDefault();
	} );
})(jQuery)