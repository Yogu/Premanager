<?php

function Dwoo_Plugin_formElement_compile(Dwoo_Compiler $compiler, $name, $label,
	$description = '', $type = '', $attributes = array())
{
	$error = "\$this->scope['errors'][$name]";
	$value = "htmlspecialchars(\$this->scope['values'][$name])";
	$attr = "'name=\"'.".$name.".'\" id=\"form-'.".$name.".'\"'.".
		"($error ? ' class=\"error\"' : '').".
		"' '.implode(' ', array_map(function(\$k, \$v){".
		"return \$k.'=\"'.htmlspecialchars(\$v).'\"';}, array_keys($attributes), ".
		"$attributes))";
	
	switch ($type) {
		case "'textarea'":
			$element = "'<textarea '.$attr.'>'.$value.'</textarea>'";
			break;
		case "'checkbox'":
			$element =
				"'<label for=\"form-'.$name.'\">'.".
				"'<input '.$attr.' type=\"checkbox\"'.".
				"($value ? ' checked=\"checked\"' : '').' />'.$label.'</label>'";
			$hideDT = true;
			break;
		default:
			$element = "'<input '.$attr.' type=\"'.$type.'\" ".
				"value=\"'.$value.'\" />'";
	}
	
	return
		"'<dl>'.".(!$hideDT ? "'<dt><label for=\"form-'.$name.'\">'.".
		"Premanager\Execution\Translation::defaultGet('Premanager', 'label', ".
		"array('label' => $label)).".
		"'</label></dt>'." : '')."'<dd>'.$element.".
		($description ? "'<p>'.$description.'</p>'." : '').
		"($error ? (".
			"'<ul class=\"input-errors\"><li>'.implode('</li><li>', $error).".
			"'</li></ul>'".
		") : '').".
		"'</dd></dl>'";
}

?>
