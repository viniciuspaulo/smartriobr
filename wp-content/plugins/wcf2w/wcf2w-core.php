<?php
//Show contact button
add_action('wp_footer', 'wcf2w_main');
function wcf2w_main($wcf2w_contact_button){
	//get options
	$wcf2w_enable_button = get_option('wcf2w_enable_button');
	$wcf2w_only_mobile = get_option('wcf2w_only_mobile');
	$wcf2w_redirect = get_option('wcf2w_redirect');
	$wcf2w_button_color = get_option('wcf2w_button_color');
	$wcf2w_icon_color = get_option('wcf2w_icon_color');
	$wcf2w_animation_button = get_option('wcf2w_animation_button');
	$wcf2w_position_button = get_option('wcf2w_position_button');
	$wcf2w_position_button_left = get_option('wcf2w_position_button_left');
	$wcf2w_position_button_right = get_option('wcf2w_position_button_right');
	$wcf2w_position_button_bottom = get_option('wcf2w_position_button_bottom');
	$wcf2w_mobile_phone = get_option('wcf2w_mobile_phone');
	$wcf2w_text_message = get_option('wcf2w_text_message');
	$wcf2w_text_tooltip = get_option('wcf2w_text_tooltip');
	
	if(!empty($wcf2w_text_message)){
		$wcf2w_txt_msg = '&text='.$wcf2w_text_message;
	} else {
		$wcf2w_txt_msg = '';
	}	

	$wcf2w_tooltip_width = get_option('wcf2w_tooltip_width');
	
	$wcf2w_showing_tooltip = get_option('wcf2w_showing_tooltip');
	$wcf2w_tooltip_text_color = get_option('wcf2w_tooltip_text_color');
	$wcf2w_tooltip_background_color = get_option('wcf2w_tooltip_background_color');

	if(!empty($wcf2w_text_tooltip) || $wcf2w_showing_tooltip != 'disable'){
		$wcf2w_tooltip_txt = 'data-title="'.$wcf2w_text_tooltip.'" ';
	} else {
		$wcf2w_tooltip_txt = '';
	}	

	if($wcf2w_showing_tooltip == 'hovering'){
		$wcf2w_showing_tooltip_option = ':hover';
	}

	if($wcf2w_showing_tooltip == 'always'){
		$wcf2w_showing_tooltip_option = '';
	}

	if($wcf2w_showing_tooltip != 'disable'){
		echo'
			<style>
				.wcf2w_tooltip {
				    position: fixed!important;
				    text-decoration: none!important;
				    outline: none!important;
				    font-size: 14px!important;
				}

	    		.wcf2w_tooltip'.$wcf2w_showing_tooltip_option.':after{
	    			content: attr(data-title)!important;
				    position: fixed!important;
				    z-index: 99999999!important;
				    background-color: '.$wcf2w_tooltip_background_color.'!important;
				    border-radius: 5px!important;
				    color: '.$wcf2w_tooltip_text_color.'!important;
				    width:'.$wcf2w_tooltip_width.'px;
				    padding: 5px!important;
	    		}
	    	</style>
		';
	}

	$animation_class = wcf2w_get_animation_class($wcf2w_animation_button);

	if($wcf2w_animation_button  == 'Forward' || $wcf2w_animation_button  == 'Backward' || $wcf2w_animation_button  == 'Buzz' || $wcf2w_animation_button  == 'Grow' || $wcf2w_animation_button  == 'Shrink'){
		$wcf2w_margin_val = '70px';
	} else {		
		$wcf2w_margin_val = '90px';
	}
		
    if($wcf2w_position_button  == 'Left'){
    	$position_style = 'left: '.$wcf2w_position_button_left.'px; bottom: '.$wcf2w_position_button_bottom.'px;';

    	if($wcf2w_showing_tooltip != 'disable'){
	    	$position_tooltip_style = '
	    		<style>
		    		.wcf2w_tooltip'.$wcf2w_showing_tooltip_option.':after{
					    margin-top: 15px;
						margin-left: '.$wcf2w_margin_val.';
						left:0px;
		    		}
		    	</style>	
	    	';
    		echo $position_tooltip_style;
    	}	
    }

    if($wcf2w_position_button  == 'Right'){
    	$position_style = 'right: '.$wcf2w_position_button_right.'px; bottom: '.$wcf2w_position_button_bottom.'px;';
    	if($wcf2w_showing_tooltip != 'disable'){
	    	$position_tooltip_style = '
	    		<style>
		    		.wcf2w_tooltip'.$wcf2w_showing_tooltip_option.':after{
	    				margin-top: 15px;
						margin-right: '.$wcf2w_margin_val.';
						right: 0px;
		    		}
		    	</style>	
	    	';
	    	echo $position_tooltip_style;
	    }	
    }
    
    //check mobile device
    $is_wcf2w_mobile_device = wcf2w_check_mobile_device();

    if($wcf2w_enable_button == 'y'){
		if($is_wcf2w_mobile_device){
			//mobile
	    	$wcf2w_whatsapp_link = 'https://api.whatsapp.com/send?phone=';
    		$wcf2w_contact_button = '<a '.$wcf2w_tooltip_txt.'href="'.$wcf2w_whatsapp_link.''.$wcf2w_mobile_phone.''.$wcf2w_txt_msg.'" class="'.$animation_class.' wcf2w_button wcf2w_tooltip" style="'.$position_style.' background-color: '.$wcf2w_button_color.'; color:'.$wcf2w_icon_color.';" target="_blank">
	    	<i class="fa fa-whatsapp"></i>
	    	</a>';
	    	echo $wcf2w_contact_button;
		} else {
			//pc
			if($wcf2w_only_mobile == 'n'){
				if(empty($wcf2w_redirect)) $wcf2w_whatsapp_link = 'https://web.whatsapp.com/send?l=en&amp;phone=';
		    	if($wcf2w_redirect == 'web') $wcf2w_whatsapp_link = 'https://web.whatsapp.com/send?l=en&amp;phone=';
				if($wcf2w_redirect == 'api') $wcf2w_whatsapp_link = 'https://api.whatsapp.com/send?phone=';
		    	$wcf2w_contact_button = '<a '.$wcf2w_tooltip_txt.'href="'.$wcf2w_whatsapp_link.''.$wcf2w_mobile_phone.''.$wcf2w_txt_msg.'" class="'.$animation_class.' wcf2w_button wcf2w_tooltip" style="'.$position_style.' background-color: '.$wcf2w_button_color.'; color:'.$wcf2w_icon_color.';" target="_blank">
		    	<i class="fa fa-whatsapp"></i>
		    	</a>';
	    		echo $wcf2w_contact_button;
		    }	
		}
	}
}	
?>