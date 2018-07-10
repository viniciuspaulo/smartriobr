<div class="surstudio_plugin_translator_revolution_lite_section surstudio_plugin_translator_revolution_lite_{{ type }}" id="section_{{ id }}">

	<div class="surstudio_plugin_translator_revolution_lite_section_loading surstudio_plugin_translator_revolution_lite_no_display"></div>

	<h3 class="surstudio_plugin_translator_revolution_lite_title">{{ title_message }}</h3>
	<div class="surstudio_plugin_translator_revolution_lite_setting">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
					<div class="dashicons-before dashicons-info"></div>
				</td>
				<td>
					<div class="surstudio_plugin_translator_revolution_lite_field">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>{{ current_message }} <span id="surstudio_plugin_translator_revolution_lite_{{ type }}_current_version">{{ current_version }}</span></td>
							</tr>
							<tr>
								<td>{{ latest_message }} <span id="surstudio_plugin_translator_revolution_lite_{{ type }}_latest_version">{{ latest_version }}</span><span id="surstudio_plugin_translator_revolution_lite_{{ type }}_whats_new"{{ whats_new.false:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ whats_new.false:end }}> | <a href="http://www.surstudio.net/translator-revolution/changelog/#since-{{ current_version }}" target="_blank">{{ whats_new_message }}</a></span></td>
							</tr>
						</table>
						
						<span id="surstudio_plugin_translator_revolution_lite_{{ type }}_up_to_date"{{ latest_installed.false:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ latest_installed.false:end }}>{{ up_to_date_message }}</span>
						
						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_update_message" class="surstudio_plugin_translator_revolution_lite_no_display">{{ update_message }}</div>
						
						<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
							<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_check">{{ button_1_message }}</button><button id="surstudio_plugin_translator_revolution_lite_{{ type }}_update"{{ verified.false:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ verified.false:end }}>{{ button_2_message }}</button><button id="surstudio_plugin_translator_revolution_lite_{{ type }}_update_now"{{ verified.true:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ verified.true:end }}>{{ button_3_message }}</button>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>

</div>