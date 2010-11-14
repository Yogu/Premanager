<?php

function Dwoo_Plugin_formElement_compile(Dwoo_Compiler $compiler, $name, $label, $description = '', $type = '') {
	$error = "\$this->scope['errors'][$name]";
	$value = "htmlspecialchars(\$this->scope['values'][$name])";
	$attributes = "'name=\"'.".$name.".'\" id=\"form-'.".$name.".'\"'.".
		"($error ? ' class=\"error\"' : '')";
	
	switch ($type) {
		case "'textarea'":
			$element = "'<textarea '.$attributes.'>'.$value.'</textarea>'";
			break;
		default:
			$element = "'<input '.$attributes.' type=\"'.$type.'\" ".
				"value=\"'.$value.'\" />'";
	} 
	
	return
		"'<dl><dt><label for=\"form-'.$name.'\">'.".
		"Premanager\Execution\Translation::defaultGet('Premanager', 'label', ".
		"array('label' => $label)).".
		"'</label></dt><dd>'.$element.".
		($description ? "'<p>'.$description.'</p>'." : '').
		"($error ? (".
			"'<ul class=\"input-errors\"><li>'.implode('</li><li>', $error).".
			"'</li></ul>'".
		") : '').".
		"'</dd></dl>'";
}

?>
