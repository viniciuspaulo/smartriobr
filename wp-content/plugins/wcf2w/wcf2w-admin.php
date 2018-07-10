<?php
//admin settings page
add_action('admin_menu', 'wcf2w_admin_menu_setup');
function wcf2w_admin_menu_setup() {
    add_submenu_page(
        'options-general.php',
        'WhatsApp Contact Button',
        'WhatsApp Contact Button',
        'manage_options',
        'wcf2w',
        'wcf2w_admin_page_screen'
    );
}

function wcf2w_admin_page_screen(){

  $wcf2w_clear_cache = 'y';

	if (isset($_POST['wcf2w_enable_button'])) {

		if(!empty($_POST['wcf2w_enable_button'])){
			update_option('wcf2w_enable_button', sanitize_text_field($_POST['wcf2w_enable_button']));
		} else {
			update_option('wcf2w_enable_button', 'n');
		}	

		if(!empty($_POST['wcf2w_only_mobile'])){
			update_option('wcf2w_only_mobile', sanitize_text_field($_POST['wcf2w_only_mobile']));
		} else {
			update_option('wcf2w_only_mobile', 'n');
		}	

		if(!empty($_POST['wcf2w_redirect'])){
			update_option('wcf2w_redirect', sanitize_text_field($_POST['wcf2w_redirect']));
		} else {
			update_option('wcf2w_redirect', 'web');
		}

		if(!empty($_POST['wcf2w_button_color'])){
			update_option('wcf2w_button_color', sanitize_text_field($_POST['wcf2w_button_color'])); 
		} else {
			update_option('wcf2w_button_color', '#00e676');
		}	

		if(!empty($_POST['wcf2w_icon_color'])){
			update_option('wcf2w_icon_color', sanitize_text_field($_POST['wcf2w_icon_color']));
		} else {
			update_option('wcf2w_icon_color', '#ffffff');
		}	

		if(!empty($_POST['wcf2w_animation_button'])){
			update_option('wcf2w_animation_button', sanitize_text_field($_POST['wcf2w_animation_button']));
		} else {
			update_option('wcf2w_animation_button', 'Tada');
		}	

		if(!empty($_POST['wcf2w_position_button'])){
			update_option('wcf2w_position_button', sanitize_text_field($_POST['wcf2w_position_button']));
		} else {
			update_option('wcf2w_position_button', 'Left');
		}    

		if(!empty($_POST['wcf2w_position_button_left'])){
			update_option('wcf2w_position_button_left', sanitize_text_field($_POST['wcf2w_position_button_left']));
		} else {
			update_option('wcf2w_position_button_left', '20');
		}    

		if(!empty($_POST['wcf2w_position_button_right'])){
			update_option('wcf2w_position_button_right', sanitize_text_field($_POST['wcf2w_position_button_right']));
		} else {
			update_option('wcf2w_position_button_right', '20');
		}    

		if(!empty($_POST['wcf2w_position_button_bottom'])){
			update_option('wcf2w_position_button_bottom', sanitize_text_field($_POST['wcf2w_position_button_bottom']));
		} else {
			update_option('wcf2w_position_button_bottom', '20');
		}    

		if(!empty($_POST['wcf2w_tooltip_width'])){
			update_option('wcf2w_tooltip_width', sanitize_text_field(wcf2w_only_numbers($_POST['wcf2w_tooltip_width'])));
		} else {
			update_option('wcf2w_tooltip_width', sanitize_text_field('100'));
		}  

		if(!empty($_POST['wcf2w_showing_tooltip'])){
			update_option('wcf2w_showing_tooltip', sanitize_text_field($_POST['wcf2w_showing_tooltip']));
		} else {
			update_option('wcf2w_showing_tooltip', sanitize_text_field('disable'));
		}	

		if(!empty($_POST['wcf2w_tooltip_text_color'])){
			update_option('wcf2w_tooltip_text_color', sanitize_text_field($_POST['wcf2w_tooltip_text_color']));
		} else {
			update_option('wcf2w_tooltip_text_color', '#ffffff');
		}   

		if(!empty($_POST['wcf2w_tooltip_background_color'])){
			update_option('wcf2w_tooltip_background_color', sanitize_text_field($_POST['wcf2w_tooltip_background_color']));
		} else {
			update_option('wcf2w_tooltip_background_color', '#00e676');
		}

		update_option('wcf2w_mobile_phone', sanitize_text_field(wcf2w_only_numbers($_POST['wcf2w_mobile_phone'])));

		update_option('wcf2w_text_message', sanitize_text_field($_POST['wcf2w_text_message']));

		update_option('wcf2w_text_tooltip', sanitize_text_field($_POST['wcf2w_text_tooltip']));

		//clear the cache
		if($wcf2w_clear_cache == 'y'){
			if(class_exists('comet_cache')){
			  comet_cache::clear();
			}

			if(class_exists('autoptimizeCache')){
			  autoptimizeCache::clearall();
			}

			if (function_exists ('wp_cache_clear_cache')) {
			  wp_cache_clear_cache();
			}
		}  

		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>'.__( 'Settings is saved', 'wcf2w' ).'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button></div>';
	}

?>
	<form method="POST" action="">
        <table class="form-table wcf2w_setting_table">
            <tr valign="top">
              <td colspan="2" style="padding: 0px;">
                <h1><i class="fa fa-whatsapp" aria-hidden="true"></i> <?php echo __('WhatsApp Contact Button', 'wcf2w'); ?></h1>
              </td>
            </tr>
            <tr valign="top">
              <td colspan="2" style="padding: 0px;">
                <h3><i class="fa fa-wrench" aria-hidden="true"></i> <?php echo __('Button Settings', 'wcf2w'); ?></h3>
              </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_enable_button">
                        <?php echo __('Enable button', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                	<?php $wcf2w_enable_button = get_option('wcf2w_enable_button');?>	
                 	<input type="radio" name="wcf2w_enable_button" id="wcf2w_enable_button" class="sp_radio" value="y" <?php if($wcf2w_enable_button=='y'){echo"checked";} ?>> yes
                    <input type="radio" name="wcf2w_enable_button" id="wcf2w_enable_button" class="sp_radio" value="n" <?php if($wcf2w_enable_button=='n'){echo"checked";} ?>> no
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_only_mobile">
                        <?php echo __('Only mobile', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <?php $wcf2w_only_mobile = get_option('wcf2w_only_mobile');?>	
                 	<input type="radio" name="wcf2w_only_mobile" id="wcf2w_only_mobile" class="sp_radio" value="y" <?php if($wcf2w_only_mobile=='y'){echo"checked";} ?>> yes
                    <input type="radio" name="wcf2w_only_mobile" id="wcf2w_only_mobile" class="sp_radio" value="n" <?php if($wcf2w_only_mobile=='n'){echo"checked";} ?>> no
                    <p><i><?php echo __('Show only on mobile devices.','wcf2w'); ?></i></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_redirect">
                        <?php echo __('Redirect', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <?php $wcf2w_redirect = get_option('wcf2w_redirect');?>	
                 	<input type="radio" name="wcf2w_redirect" id="wcf2w_redirect" class="sp_radio" value="api" <?php if($wcf2w_redirect=='api'){echo"checked";} ?>> api.whatsapp.com
                    <input type="radio" name="wcf2w_redirect" id="wcf2w_redirect" class="sp_radio" value="web" <?php if($wcf2w_redirect=='web'){echo"checked";} ?>> web.whatsapp.com
                    <p><i><?php echo __('Redirect for desktop.','wcf2w'); ?></i></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_button_color">
                        <?php echo __('Button Color', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_button_color" id="wcf2w_button_color" value="<?php echo get_option('wcf2w_button_color')?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_icon_color">
                        <?php echo __('Icon Color', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_icon_color" id="wcf2w_icon_color" value="<?php echo get_option('wcf2w_icon_color')?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_position_button">
                        <?php echo __('Position Button', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <select name="wcf2w_position_button" id="wcf2w_position_button">
                    <?php 
                      $wcf2w_position_button = get_option('wcf2w_position_button');
                      if($wcf2w_position_button == 'Left'){
                        ?><option value="<?php echo $wcf2w_position_button;?>"><?php echo __('Left', 'wcf2w'); ?></option><?php
                      }

                      if($wcf2w_position_button == 'Right'){
                        ?><option value="<?php echo $wcf2w_position_button;?>"><?php echo __('Right', 'wcf2w'); ?></option><?php
                      }
                    ?>
                  	<option disabled>---</option>
                  	<option value="Left"><?php echo __('Left', 'wcf2w'); ?></option>
                  	<option value="Right"><?php echo __('Right', 'wcf2w'); ?></option>
                  </select>
                  <p><i><?php echo __('Select position of page.','wcf2w'); ?></i></p>
                </td>  
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_position_button_left">
                        <i><?php echo __('Left', 'wcf2w'); ?>:</i>
                    </label>
                </th>
                <td>
                  <input type="number" name="wcf2w_position_button_left" id="wcf2w_position_button_left" value="<?php echo get_option('wcf2w_position_button_left')?>" style="width:60px;"> px
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_position_button_right">
                        <i><?php echo __('Right', 'wcf2w'); ?>:</i>
                    </label>
                </th>
                <td>
                  <input type="number" name="wcf2w_position_button_right" id="wcf2w_position_button_right" value="<?php echo get_option('wcf2w_position_button_right')?>" style="width:60px;"> px
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_position_button_bottom">
                        <i><?php echo __('Bottom', 'wcf2w'); ?>:</i>
                    </label>
                </th>
                <td>
                  <input type="number" name="wcf2w_position_button_bottom" id="wcf2w_position_button_bottom" value="<?php echo get_option('wcf2w_position_button_bottom')?>" style="width:60px;"> px
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_animation_button">
                        <?php echo __('Animation Button', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <select name="wcf2w_animation_button" id="wcf2w_animation_button">
                  	<option value="<?php echo get_option('wcf2w_animation_button')?>"><?php echo get_option('wcf2w_animation_button')?></option>
                  	<option disabled>---</option>
                  	<option value="None">None</option>
                  	<option value="Rotate">Rotate</option>
                  	<option value="Tada">Tada</option>
                  	<option value="Swing">Swing</option>
                  	<option value="Grow">Grow</option>
                  	<option value="Shrink">Shrink</option>
                    <option value="Buzz">Buzz</option>
                    <option value="Forward">Forward</option>
                    <option value="Backward">Backward</option>
                  </select>
                  <p><i><?php echo __('Select animation effect', 'wcf2w'); ?></i></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_mobile_phone">
                        <?php echo __('Mobile Phone', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  + <input type="number" name="wcf2w_mobile_phone" id="wcf2w_mobile_phone" value="<?php echo get_option('wcf2w_mobile_phone')?>"></br>
                  <p><i><?php echo __('Use mobile phone number without "+" character. Example: Introduce 79094459070 for (+7) 909-445-90-70.', 'wcf2w'); ?></i></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_text_message">
                        <?php echo __('Message', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_text_message" id="wcf2w_text_message" value="<?php echo get_option('wcf2w_text_message')?>">
                </td>
            </tr>
            <tr valign="top">
                <td colspan="2" style="padding: 0px;">
                  <h3><i class="fa fa-tag" aria-hidden="true"></i> <?php echo __('Tooltip Settings', 'wcf2w'); ?></h3>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_text_tooltip">
                        <?php echo __('Text', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_text_tooltip" id="wcf2w_text_tooltip" value="<?php echo get_option('wcf2w_text_tooltip')?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_tooltip_width">
                        <?php echo __('Width', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="number" name="wcf2w_tooltip_width" id="wcf2w_tooltip_width" value="<?php echo get_option('wcf2w_tooltip_width')?>" style="width:60px;">px
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_showing_tooltip">
                        <?php echo __('Showing', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <?php $wcf2w_showing_tooltip = get_option('wcf2w_showing_tooltip');?> 
                  <input type="radio" name="wcf2w_showing_tooltip" id="wcf2w_showing_tooltip" class="sp_radio" value="hovering" <?php if($wcf2w_showing_tooltip=='hovering'){echo"checked";} ?>><?php echo __('Hover', 'wcf2w'); ?>
                  <input type="radio" name="wcf2w_showing_tooltip" id="wcf2w_showing_tooltip" class="sp_radio" value="always" <?php if($wcf2w_showing_tooltip=='always'){echo"checked";} ?>><?php echo __('Always', 'wcf2w'); ?>
                  <input type="radio" name="wcf2w_showing_tooltip" id="wcf2w_showing_tooltip" class="sp_radio" value="disable" <?php if($wcf2w_showing_tooltip=='disable'){echo"checked";} ?>><?php echo __('Disable', 'wcf2w'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_tooltip_text_color">
                        <?php echo __('Text Color', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_tooltip_text_color" id="wcf2w_tooltip_text_color" value="<?php echo get_option('wcf2w_tooltip_text_color')?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="wcf2w_tooltip_background_color">
                        <?php echo __('Background Color', 'wcf2w'); ?>:
                    </label>
                </th>
                <td>
                  <input type="text" name="wcf2w_tooltip_background_color" id="wcf2w_tooltip_background_color" value="<?php echo get_option('wcf2w_tooltip_background_color')?>">
                </td>
            </tr>
        </table>
		<p><input type="submit" value="<?php echo __('Save', 'wcf2w'); ?>" class="button-primary"/></p>
	</form>
<?php	
}	