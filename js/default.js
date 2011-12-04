$(document).ready(function() {
	if (!Modernizr.input.placeholder) {
		$("input[placeholder], textarea[placeholder]").each(function() {
			if ($(this).val() == "") {
				$(this).val($(this).attr("placeholder"));
				$(this).focus(function() {
					if ($(this).val() == $(this).attr("placeholder")) {
						$(this).val("");
						$(this).removeClass('placeholder');
					}
				});
				$(this).blur(function() {
					if ($(this).val() == "") {
						$(this).val($(this).attr("placeholder"));
						$(this).addClass('placeholder');
					}
				});
			}
		});
	}
	
	// Process Focus and Blur event to change background color of input controls
	$("input").focus(function() {
		$(this).removeClass("blurred").addClass("focused");
	});
	
	$("input").blur(function() {
		$(this).removeClass("focused").addClass("blurred");
	});
	//
	
});