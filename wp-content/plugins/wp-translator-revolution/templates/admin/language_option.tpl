		<div class="surstudio_plugin_translator_revolution_lite_language_item">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="checkbox" name="{{ name }}[]" value="{{ option }}"{{ checked.true:begin }} checked="checked"{{ checked.true:end }} id="{{ id }}" class="surstudio_plugin_translator_revolution_lite_checkbox" /></td>
					<td><label for="{{ id }}"><img src="{{ base_url }}{{ value_formatted }}.gif" alt="{{ value }}" /></label></td>
					<td><label for="{{ id }}">{{ value }}</label></td>
				</tr>
			</table>
			<ul class="surstudio_plugin_translator_revolution_lite_no_display">
				<li class="surstudio_plugin_translator_revolution_lite_language_order_item">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><img src="{{ base_url }}{{ value_formatted }}.gif" alt="{{ value }}" /></td>
							<td>{{ value }}</td>
						</tr>
					</table>
					<input name="{{ id }}_order" type="hidden" value="{{ option }}" />
				</li>
			</ul>
		</div>