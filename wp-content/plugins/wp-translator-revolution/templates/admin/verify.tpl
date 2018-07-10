<div class="surstudio_plugin_translator_revolution_lite_section surstudio_plugin_translator_revolution_lite_{{ type }}" id="section_{{ id }}">

	<div class="surstudio_plugin_translator_revolution_lite_section_loading surstudio_plugin_translator_revolution_lite_no_display"></div>

	<div class="surstudio_plugin_translator_revolution_lite_title_container">
		<h3 class="surstudio_plugin_translator_revolution_lite_title">{{ title_message }}</h3>
		<button{{ verified.true:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ verified.true:end }}>{{ button_1_message }}</button>
	</div>
	
	<div class="surstudio_plugin_translator_revolution_lite_setting surstudio_plugin_translator_revolution_lite_no_display" id="surstudio_plugin_translator_revolution_lite_{{ type }}_info">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
					<div class="dashicons-before dashicons-plus-alt"></div>
				</td>
				<td>

					<div class="surstudio_plugin_translator_revolution_lite_field">
					
						<div class="surstudio_plugin_translator_revolution_lite_sub_title" style="padding-bottom: 10px;">{{ why_message }}</div>
						
						<div class="surstudio_plugin_translator_revolution_lite_sub_title">{{ sub_title_message }}</div>

						<ul>
							<li>{{ description_1_message }}</li>
							<li>{{ description_2_message }}</li>
							<li>{{ description_3_message }}</li>
						</ul>
					
					</div>

				</td>
			</tr>
		</table>

	</div>

	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>
	
	<div class="surstudio_plugin_translator_revolution_lite_setting">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
					<div class="dashicons-before dashicons-unlock"></div>
				</td>
				<td>

					<div class="surstudio_plugin_translator_revolution_lite_field">
						
						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_step_1"{{ verified.true:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ verified.true:end }}>
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>{{ item_purchase_code_formatted }}</td>
								</tr>
								<tr>
									<td>{{ email_formatted }}</td>
								</tr>
							</table>
							<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
								<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_register">{{ button_2_message }}</button>
							</div>
						</div>

						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_step_2" class="surstudio_plugin_translator_revolution_lite_no_display">
						
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="padding-bottom: 15px;">{{ verification_code_description_message }}</td>
								</tr>
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td class="surstudio_plugin_translator_revolution_lite_label">{{ verification_code_title_message }}</td>
												<td><input class="surstudio_plugin_translator_revolution_lite_input" name="surstudio_verify_verification_code" id="surstudio_verify_verification_code" type="text" value=""></td>
											</tr>
											<tr>
												<td></td>
												<td style="padding-top: 0;">{{ verification_code_sub_title_message }}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
								<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_back">{{ button_4_message }}</button><button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_verify">{{ button_3_message }}</button>
							</div>
						</div>

						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_step_3"{{ verified.false:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ verified.false:end }}>
						
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="padding-top: 5px; padding-bottom: 5px; font-size: 14px;"><strong>{{ complete_title_message }}</strong></td>
								</tr>
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td class="surstudio_plugin_translator_revolution_lite_label"><span class="surstudio_plugin_translator_revolution_lite_verify_icon dashicons-before dashicons-backup"></span><strong>{{ complete_1_title_message }}</strong></td>
											</tr>
											<tr>
												<td style="padding-top: 0;padding-bottom: 10px;">{{ complete_1_description_message }}</td>
											</tr>
											<tr>
												<td class="surstudio_plugin_translator_revolution_lite_label"><span class="surstudio_plugin_translator_revolution_lite_verify_icon dashicons-before dashicons-sos"></span><strong>{{ complete_2_title_message }}</strong></td>
											</tr>
											<tr>
												<td style="padding-top: 0;padding-bottom: 10px;">{{ complete_2_description_message }}<br /><span id="surstudio_plugin_translator_revolution_lite_support_pin">{{ complete_2_priority_pin }} <strong>{{ support_pin }}</strong></span></td>
											</tr>
											<tr>
												<td class="surstudio_plugin_translator_revolution_lite_label"><span class="surstudio_plugin_translator_revolution_lite_verify_icon dashicons-before dashicons-update"></span><strong>{{ complete_3_title_message }}</strong></td>
											</tr>
											<tr>
												<td style="padding-top: 0;padding-bottom: 10px;">{{ complete_3_description_message }}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						
						</div>

					</div>

				</td>
			</tr>
		</table>

	</div>

	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>

</div>
