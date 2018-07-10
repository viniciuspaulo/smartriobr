<div id="surstudio_plugin_translator_revolution_lite_settings">

<form method="post" enctype="multipart/form-data" action="" name="surstudio_admin" id="surstudio_admin">

<div class="surstudio_plugin_translator_revolution_lite_page_title_container">
	<div class="surstudio_plugin_translator_revolution_lite_da_icon_main">
		<div class="dashicons-before dashicons-translation"></div>
	</div>
	<h2 class="surstudio_plugin_translator_revolution_lite_page_title">Translator Revolution</h2>
	<button type="button" onclick="window.open('{{ welcome_url }}', '_self');">{{ welcome_message }}</button>
</div>

{{ api_key_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_api_key_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_api_key_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ api_key_validate.false:end }}

{{ api_key_empty_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_api_key_empty_validate surstudio_plugin_translator_revolution_lite_message surstudio_plugin_translator_revolution_lite_warning_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_api_key_empty_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ api_key_empty_validate.false:end }}

{{ api_key_google_empty_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_api_key_google_empty_validate surstudio_plugin_translator_revolution_lite_message surstudio_plugin_translator_revolution_lite_warning_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_api_key_google_empty_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ api_key_google_empty_validate.false:end }}

{{ cache_folder_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_cache_folder_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-warning"></div>
			</td>
			<td><p>{{ general_cache_folder_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ cache_folder_validate.false:end }}

{{ cache_file_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_cache_file_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-warning"></div>
			</td>
			<td><p>{{ general_cache_file_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ cache_file_validate.false:end }}

{{ openssl_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_openssl_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_openssl_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ openssl_validate.false:end }}

{{ fsockopen_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_fsockopen_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_fsockopen_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ fsockopen_validate.false:end }}

{{ mcrypt_validate.false:begin }}
<div class="surstudio_plugin_translator_revolution_lite_mcrypt_validate surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ general_mcrypt_validate_message }}</p></td>
		</tr>
	</table>
</div>
{{ mcrypt_validate.false:end }}

{{ just_saved.true:begin }}
<div class="surstudio_plugin_translator_revolution_lite_saved surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-yes"></div>
			</td>
			<td><p>{{ saved_message }}</p></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
SurStudioPluginTranslatorRevolutionLiteAdmin.hideMessage(".surstudio_plugin_translator_revolution_lite_saved", 1000);
</script>
{{ just_saved.true:end }}

{{ just_imported_success.true:begin }}
<div class="surstudio_plugin_translator_revolution_lite_imported_success surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-yes"></div>
			</td>
			<td><p>{{ advanced_import_success_message }}</p></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
SurStudioPluginTranslatorRevolutionLiteAdmin.hideMessage(".surstudio_plugin_translator_revolution_lite_imported_success", 3000);
</script>
{{ just_imported_success.true:end }}

{{ just_imported_fail.true:begin }}
<div class="surstudio_plugin_translator_revolution_lite_imported_fail surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-no"></div>
			</td>
			<td><p>{{ advanced_import_fail_message }}</p></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
SurStudioPluginTranslatorRevolutionLiteAdmin.hideMessage(".surstudio_plugin_translator_revolution_lite_imported_fail", 10000);
</script>
{{ just_imported_fail.true:end }}

{{ just_reseted.true:begin }}
<div class="surstudio_plugin_translator_revolution_lite_reseted surstudio_plugin_translator_revolution_lite_message">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="surstudio_plugin_translator_revolution_lite_da_icon_container">
				<div class="dashicons-before dashicons-yes"></div>
			</td>
			<td><p>{{ reseted_message }}</p></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
SurStudioPluginTranslatorRevolutionLiteAdmin.hideMessage(".surstudio_plugin_translator_revolution_lite_reseted", 1000);
</script>
{{ just_reseted.true:end }}

<div class="surstudio_plugin_translator_revolution_lite_admin_container">

	<div class="surstudio_plugin_translator_revolution_lite_submit_top_container">
		<button type="submit" class="surstudio_plugin_translator_revolution_lite_button_save" name="save_top" {{ just_imported_translations_success.true:begin }} style="display: none;"{{ just_imported_translations_success.true:end }}>{{ save_button_message }}</button>
	</div>

	<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_container" id="surstudio_plugin_translator_revolution_lite_main_navigation">
		<ul>
			<li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ general.show.false:begin }}un{{ general.show.false:end }}selected" id="general_menu">
				<div class="dashicons-before dashicons-admin-settings"></div>
				<span><span>{{ general_message }}</span></span>
			</li> 
			<li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ advanced.show.false:begin }}un{{ advanced.show.false:end }}selected" id="advanced_menu">
				<div class="dashicons-before dashicons-admin-generic"></div>
				<span><span>{{ advanced_message }}</span></span>
			</li> 
			<li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ translations.show.false:begin }}un{{ translations.show.false:end }}selected" id="translations_menu">
				<div class="dashicons-before dashicons-index-card"></div>
				<span><span>{{ translations_message }}</span></span>
			</li>
			<li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ backup.show.false:begin }}un{{ backup.show.false:end }}selected" id="backup_menu">
				<div class="dashicons-before dashicons-backup"></div>
				<span><span>{{ backup_message }}</span></span>
			</li>
{{ support.enabled.true:begin }}
			<li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ support.show.false:begin }}un{{ support.show.false:end }}selected" id="support_menu">
				<div class="dashicons-before dashicons-sos"></div>
				<span><span>{{ support_message }}</span></span>
			</li> 
{{ support.enabled.true:end }}
		</ul>
	</div>

	<div class="surstudio_plugin_translator_revolution_lite_main_form_container">
	
		<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_main_container">

			<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ general.show.false:begin }}no_{{ general.show.false:end }}display" id="general_tab">
				<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">

					{{ group_1 }}

				</div>
			</div>

			<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ advanced.show.false:begin }}no_{{ advanced.show.false:end }}display" id="advanced_tab">
				<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">

					<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_container surstudio_plugin_translator_revolution_lite_ui_tabs_container_alt">
						<ul>
						   <li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ advanced_general.show.false:begin }}un{{ advanced_general.show.false:end }}selected" id="advanced_general_menu"><div class="dashicons-before dashicons-admin-tools"></div><span><span>{{ advanced_general_message }}</span></span></li> 
						   <li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ advanced_import_export.show.false:begin }}un{{ advanced_import_export.show.false:end }}selected" id="advanced_import_export_menu"><div class="dashicons-before dashicons-migrate"></div><span><span>{{ advanced_import_export_message }}</span></span></li> 
						</ul>
					</div>

					<div class="surstudio_plugin_translator_revolution_lite_main_form_container">
			
						<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_main_container">

							<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ advanced_general.show.false:begin }}no_{{ advanced_general.show.false:end }}display" id="advanced_general_tab">

								<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">
									
										{{ group_2 }}

								</div>

							</div>

							<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ advanced_import_export.show.false:begin }}no_{{ advanced_import_export.show.false:end }}display" id="advanced_import_export_tab">

								<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">
									
									{{ group_3 }}
		
								</div>
									
							</div>
						
						</div>
						
					</div>

				</div>
			</div>

			<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ translations.show.false:begin }}no_{{ translations.show.false:end }}display" id="translations_tab">
				<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">

					<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_container surstudio_plugin_translator_revolution_lite_ui_tabs_container_alt">
						<ul>
						   <li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ translations_general.show.false:begin }}un{{ translations_general.show.false:end }}selected" id="translations_general_menu"><div class="dashicons-before dashicons-list-view"></div><span><span>{{ translations_general_message }}</span></span></li> 
						   <li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ translations_import.show.false:begin }}un{{ translations_import.show.false:end }}selected" id="translations_import_menu"><div class="dashicons-before dashicons-download"></div><span><span>{{ translations_import_message }}</span></span></li> 
						   <li class="surstudio_plugin_translator_revolution_lite_ui_tab surstudio_plugin_translator_revolution_lite_ui_tab_{{ translations_export.show.false:begin }}un{{ translations_export.show.false:end }}selected" id="translations_export_menu"><div class="dashicons-before dashicons-upload"></div><span><span>{{ translations_export_message }}</span></span></li> 
						</ul>
					</div>

					<div class="surstudio_plugin_translator_revolution_lite_main_form_container">
			
						<div class="surstudio_plugin_translator_revolution_lite_ui_tabs_main_container">

							<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ translations_general.show.false:begin }}no_{{ translations_general.show.false:end }}display" id="translations_general_tab">

								<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">
									
										{{ group_4 }}

								</div>

							</div>

							<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ translations_import.show.false:begin }}no_{{ translations_import.show.false:end }}display" id="translations_import_tab">

								<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">
									
									{{ group_6 }}
		
								</div>
									
							</div>
						
							<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ translations_export.show.false:begin }}no_{{ translations_export.show.false:end }}display" id="translations_export_tab">

								<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">
									
									{{ group_7 }}
		
								</div>
									
							</div>
						
						</div>
						
					</div>

				</div>
			</div>
			
			<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ support.show.false:begin }}no_{{ support.show.false:end }}display" id="backup_tab">
				<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">

					{{ group_8 }}

				</div>
			</div>
			
{{ support.enabled.true:begin }}
			<div class="surstudio_plugin_translator_revolution_lite_ui_tab_container surstudio_plugin_translator_revolution_lite_{{ support.show.false:begin }}no_{{ support.show.false:end }}display" id="support_tab">
				<div class="surstudio_plugin_translator_revolution_lite_ui_tab_content">

					{{ group_5 }}

				</div>
			</div>
{{ support.enabled.true:end }}			
		</div>

		<div class="surstudio_plugin_translator_revolution_lite_submit_container">

			<button class="surstudio_plugin_translator_revolution_lite_button_reset" type="button"  onclick="return SurStudioPluginTranslatorRevolutionLiteAdmin.resetSettings('{{ reset_message }}');"{{ just_imported_translations_success.true:begin }} style="display: none;"{{ just_imported_translations_success.true:end }} >{{ reset_button_message }}</button>
		
			<button class="surstudio_plugin_translator_revolution_lite_button_save" type="submit" name="save"{{ just_imported_translations_success.true:begin }} style="display: none;"{{ just_imported_translations_success.true:end }}>{{ save_button_message }}</button>

			<input type="hidden" name="surstudio_plugin_translator_revolution_lite_admin_action" id="surstudio_plugin_translator_revolution_lite_admin_action" value="surstudio_plugin_translator_revolution_lite_save_settings" />
			<input type="hidden" name="surstudio_tab" id="surstudio_tab" value="{{ tab }}" />
			<input type="hidden" name="surstudio_tab_2" id="surstudio_tab_2" value="{{ tab_2 }}" />
			<input type="hidden" name="surstudio_tab_3" id="surstudio_tab_3" value="{{ tab_3 }}" />

		</div>

	</div>
	
</div>

</form>

</div>

<script type="text/javascript">
/*<![CDATA[*/
SurStudioPluginTranslatorRevolutionLiteAdmin.initialize("{{ ajax_url }}");
/*]]>*/
</script>
