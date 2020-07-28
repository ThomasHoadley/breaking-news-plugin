(function ($) {
	$(function () {
		var $bar = $($(".th-breaking-news")[0]);
		if ($bar.length) {
			var $insertAfter = $bar.data("selector");

			if ($($insertAfter).length) {
				$bar.detach().insertAfter($($insertAfter)[0]);
			} else {
				console.log("The Breaking News bar is appended to the top of the page. Please add a valid selector if you wish to move it.");
				$("body").prepend($bar);
			}
		}
	});
})(jQuery);
