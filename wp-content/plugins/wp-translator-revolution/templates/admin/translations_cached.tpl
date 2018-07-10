	<h3 class="surstudio_plugin_translator_revolution_lite_title surstudio_plugin_translator_revolution_lite_title_10">
		Cached translations (<span class="surstudio_plugin_translator_revolution_lite_selected_label">{{ from_language }} &gt; {{ to_language }}</span>)
		<span class="surstudio_plugin_translator_revolution_lite_cached_translations_search_container">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><input class="surstudio_plugin_translator_revolution_lite_input" name="surstudio_translations_search" id="surstudio_translations_search" type="text" value="" onkeypress="return SurStudioPluginTranslatorRevolutionLiteAdmin.preSearchTranslation(event);" /></td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td><input class="button submit-button reset-button" type="button" value="Search" onclick="SurStudioPluginTranslatorRevolutionLiteAdmin.searchTranslation(); return false;">
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td><input class="button submit-button reset-button" type="button" value="Reset" onclick="SurStudioPluginTranslatorRevolutionLiteAdmin.resetSearchTranslation(true); return false;">
				</tr>
			</table>
		</span>
	</h3>
	<div class="surstudio_plugin_translator_revolution_lite_setting">
		<div class="surstudio_plugin_translator_revolution_lite_field surstudio_plugin_translator_revolution_lite_cached_translations_container">
			<table border="0" cellpadding="0" cellspacing="0">
{{ cached_translations_fields_formatted }}
			</table>
		</div>	
	</div>
