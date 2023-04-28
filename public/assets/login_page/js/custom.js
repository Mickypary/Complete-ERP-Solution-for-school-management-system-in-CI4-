(function($) {
	'use strict';
	
	$(document).ready(function () {
		$.backstretch([
			base_url + "uploads/login_image/slider_1.jpg",
			base_url + "uploads/login_image/slider_2.jpg",
			base_url + "uploads/login_image/slider_3.jpg",
		],{duration: 3000, fade: 750});
	});
	
})(jQuery);
