(function ($) {
	$(function () {
		$(".th-breaking-news-color-field").wpColorPicker();

		$(".th-breaking-news-timepicker").datetimepicker({
			format: "Y-m-d H:i:s",
		});

		var dateField = $('input[name="th_breaking_news_expiry_date"]');

		$('input[name="th_breaking_news_expiry_date_checked"]').change(function () {
			if (this.checked) {
				dateField.css("display", "inline-block");
			} else {
				dateField.val("");
				dateField.css("display", "none");
			}
		});
	});
})(jQuery);
