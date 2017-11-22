var scroll = {
	init: function(o) {
		var str = o;
		$(str).appendTo('body');

		$(".scroll_top").click(function() {
			$(window).scrollTop(0);
		});

		$(window).scroll(function() {
			if ($(window).scrollTop() > 1024) {
				$(".scroll_top").show();
			} else {
				$(".scroll_top").hide();
			}
			if ($(window).scrollTop() > 500) {
				$("#fixedTop").show();
			} else {
				$("#fixedTop").hide();
			}
		});
	}
}