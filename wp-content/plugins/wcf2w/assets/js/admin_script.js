jQuery(document).ready(function($) {
	$('input[name*="color"]').wpColorPicker();

	var wcf2w_position_button = $("#wcf2w_position_button").val();
	if(wcf2w_position_button == "Left"){
		$("#wcf2w_position_button_right").prop("disabled", true);
		$("#wcf2w_position_button_left").prop("disabled", false);
	}
	if(wcf2w_position_button == "Right"){
		$("#wcf2w_position_button_right").prop("disabled", false);
		$("#wcf2w_position_button_left").prop("disabled", true);
	}
	
	$("#wcf2w_position_button").change(function() {
		var wcf2w_position_button = $("#wcf2w_position_button").val();
		if(wcf2w_position_button == "Left"){
			$("#wcf2w_position_button_right").prop("disabled", true);
			$("#wcf2w_position_button_left").prop("disabled", false);
		}
		if(wcf2w_position_button == "Right"){
			$("#wcf2w_position_button_right").prop("disabled", false);
			$("#wcf2w_position_button_left").prop("disabled", true);
		}
	});	

});		