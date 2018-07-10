{{ verified.false:begin }}<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_disabled" class="surstudio_plugin_translator_revolution_lite_section_disabled">
	<table border="0" cellpadding="0" cellspacing="0">
	  <tr>
		 <td>
			  {{ not_enabled_message }}
		 </td>
	  </tr>
	</table>
</div>{{ verified.false:end }}{{ verified.true:begin }}
<div id="surstudio_plugin_translator_revolution_lite_{{ type }}_loading" class="surstudio_plugin_translator_revolution_lite_section_loading surstudio_plugin_translator_revolution_lite_no_display"></div>
<div class="surstudio_plugin_translator_revolution_lite_section surstudio_plugin_translator_revolution_lite_{{ type }}{{ dependence.show.false:begin }} surstudio_plugin_translator_revolution_lite_no_display{{ dependence.show.false:end }}{{ has_dependence.true:begin }} surstudio_plugin_translator_revolution_lite_section_tabbed_{{ dependence_count }}{{ has_dependence.true:end }}" id="section_{{ id }}">
	
	<div class="surstudio_plugin_translator_revolution_lite_tooltip"></div>
	<div class="surstudio_plugin_translator_revolution_lite_description surstudio_plugin_translator_revolution_lite_no_display">{{ description_message }}</div>
		
	<h3 class="surstudio_plugin_translator_revolution_lite_title">{{ title_message }}</h3>
	<div class="surstudio_plugin_translator_revolution_lite_setting">
		<div class="surstudio_plugin_translator_revolution_lite_field">
{{ type_formatted }}
			<input id="{{ id }}_backup_button" class="button submit-button reset-button" type="button" value="{{ backup_button_message }}" onclick="SurStudioPluginTranslatorRevolutionLiteBackup.run(); return false;" />
		</div>	
	</div>
	
	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>
	
	<h3 class="surstudio_plugin_translator_revolution_lite_title" style="padding-top: 15px; margin-bottom: 15px;">{{ backup_title_message }}</h3>
	
	<div id="{{ id }}_restore_message" class="surstudio_plugin_translator_revolution_lite_no_display">{{ restore_message }}</div>
	
	<div id="{{ id }}_online_backups" class="surstudio_plugin_translator_revolution_lite_no_display"></div>
	
	<input id="{{ id }}_list_button" class="button submit-button reset-button" type="button" value="{{ list_button_message }}" onclick="SurStudioPluginTranslatorRevolutionLiteBackup.list(); return false;" />
	
	
	
	{{ has_dependence.true:begin }}
	<input type="hidden" name="{{ id }}_dependence" id="{{ id }}_dependence" value="{{ formatted_dependence }}" />
	<input type="hidden" name="{{ id }}_dependence_show_value" id="{{ id }}_dependence_show_value" value="{{ formatted_dependence_show_value }}" />
	{{ has_dependence.true:end }}	
	<div class="surstudio_plugin_translator_revolution_lite_clear"></div>
	
</div>
{{ verified.true:end }}