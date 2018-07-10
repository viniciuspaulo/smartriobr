{{ on_before_load.empty.false:begin }}
<script type="text/javascript">
/*<![CDATA[*/
{{ on_before_load }}
/*]]>*/
</script>
{{ on_before_load.empty.false:end }}
<script type="text/javascript" src="{{ url_library }}"></script>
<script type="text/javascript">
/*<![CDATA[*/
jQuery.translator.loader({
{{ options_formatted }}	clsKey: "ac5b153462e694fc2d5d1579ab928522"
});
/*]]>*/
</script>