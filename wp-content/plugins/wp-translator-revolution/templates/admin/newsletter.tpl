<div class="surstudio_plugin_translator_revolution_lite_section surstudio_plugin_translator_revolution_lite_{{ type }}" id="section_{{ id }}">

	<div class="surstudio_plugin_translator_revolution_lite_section_loading surstudio_plugin_translator_revolution_lite_no_display"></div>

	<div class="surstudio_plugin_translator_revolution_lite_title_container">
		<h3 class="surstudio_plugin_translator_revolution_lite_title">{{ title_message }}</h3>
		<button{{ subscribed.true:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ subscribed.true:end }}>{{ button_1_message }}</button>
	</div>
	
	<div class="surstudio_plugin_translator_revolution_lite_setting surstudio_plugin_translator_revolution_lite_no_display" id="surstudio_plugin_translator_revolution_lite_{{ type }}_info">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
					<div class="dashicons-before dashicons-plus-alt"></div>
				</td>
				<td>
					<div class="surstudio_plugin_translator_revolution_lite_field">
					
						<div class="surstudio_plugin_translator_revolution_lite_sub_title">{{ sub_title_message }}</div>

						<ul>
							<li>{{ description_1_message }}</li>
							<li>{{ description_2_message }}</li>
						</ul>
						
						<div class="surstudio_plugin_translator_revolution_lite_sub_title" style="padding-bottom: 15px;">{{ description_3_message }}</div>
					
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
					<div class="dashicons-before dashicons-email-alt"></div>
				</td>
				<td>
					<div class="surstudio_plugin_translator_revolution_lite_field">
						
						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_step_1"{{ subscribed.true:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ subscribed.true:end }}>						
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>{{ subscribe_formatted }}</td>
								</tr>
							</table>
							<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
								<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_subscribe">{{ button_2_message }}</button>
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
												<td><input class="surstudio_plugin_translator_revolution_lite_input" name="surstudio_newsletter_verification_code" id="surstudio_newsletter_verification_code" type="text" value=""></td>
											</tr>
											<tr>
												<td></td>
												<td style="padding-top: 0;">Enter the 32 characters verification code</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
								<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_back">{{ button_4_message }}</button><button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_verify">{{ button_3_message }}</button>
							</div>
						</div>

						<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_step_3"{{ subscribed.false:begin }} class="surstudio_plugin_translator_revolution_lite_no_display"{{ subscribed.false:end }}>
						
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td style="padding-top: 5px; padding-bottom: 5px; font-size: 14px;"><strong>You're subscribed to our newsletter!</strong></td>
								</tr>
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td><span class="surstudio_plugin_translator_revolution_lite_verify_icon dashicons-before dashicons-update"></span><strong>{{ description_1_message }}</strong></td>
											</tr>
											<tr>
												<td><span class="surstudio_plugin_translator_revolution_lite_verify_icon dashicons-before dashicons-feedback"></span><strong>{{ description_2_message }}</strong></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding-bottom: 15px;">You're subscribed using your <em>{{ email }}</em> e-mail address.</td>
								</tr>
							</table>
							<div class="surstudio_plugin_translator_revolution_lite_{{ type }}_button_container">
								<button id="surstudio_plugin_translator_revolution_lite_{{ type }}_button_unsubscribe">{{ button_5_message }}</button>
							</div>						
						</div>

					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>

</div>
