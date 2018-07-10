<div class="surstudio_plugin_translator_revolution_lite_section surstudio_plugin_translator_revolution_lite_{{ type }}{{ dependence.show.false:begin }} surstudio_plugin_translator_revolution_lite_no_display{{ dependence.show.false:end }}{{ has_dependence.true:begin }} surstudio_plugin_translator_revolution_lite_section_tabbed_{{ dependence_count }}{{ has_dependence.true:end }}" id="section_{{ id }}">
	
	<div class="surstudio_plugin_translator_revolution_lite_tooltip"></div>
	<div class="surstudio_plugin_translator_revolution_lite_description surstudio_plugin_translator_revolution_lite_no_display">{{ description_message }}</div>
		
	<h3 class="surstudio_plugin_translator_revolution_lite_title">{{ title_message }}</h3>

	<div class="surstudio_plugin_translator_revolution_lite_setting">
		<div class="surstudio_plugin_translator_revolution_lite_field">
			{{ empty_collection.false:begin }}
			{{ collection_formatted }}
			{{ empty_collection.false:end }}
			{{ empty_collection.true:begin }}
			<div class="surstudio_plugin_translator_revolution_lite_validate_warning">{{ empty_collection_message }}</div>
			{{ empty_collection.true:end }}
		</div>	
	</div>

	{{ empty_collection.false:begin }}
	<div id="surstudio_translations_export_translations_buttons">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<input class="button-primary export-translations" type="submit" name="{{ id }}_button" value="{{ button_message }}" />
				</td>
			</tr>
		</table>
	</div>
	{{ empty_collection.false:end }}
	
	{{ has_dependence.true:begin }}
	<input type="hidden" name="{{ id }}_dependence" id="{{ id }}_dependence" value="{{ formatted_dependence }}" />
	<input type="hidden" name="{{ id }}_dependence_show_value" id="{{ id }}_dependence_show_value" value="{{ formatted_dependence_show_value }}" />
	{{ has_dependence.true:end }}	
	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>
	
</div>
