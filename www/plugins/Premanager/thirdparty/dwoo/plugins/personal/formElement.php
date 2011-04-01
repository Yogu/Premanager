<?php

/**
 * Inserts a form element
 * 
 * <dl>
 *   <dt><label for="form-NAME">LABEL:</label></dt> *
 *   <dd>
 *     ELEMENT
 *     <p>DESCRIPTION</p> *
 *     <ul class="input-errors"><li>ERROR 1</li><li>ERROR 2</li></ul> *
 *   </dd>
 * </dl>
 * 
 * * optional
 * 
 * ELEMENT depends on $type parameter:
 *  - textarea: <textarea ATT>VALUE</textarea>
 *  - checkbox: <dt> hidden
 *    <label for="form-NAME">
 *      <input ATT type="checkbox" checked="checked"* />
 *      LABEL
 *    </label>
 *  - select:
 *    $option parameter: array(KEY => VALUE)
 *      1) KEY: option name; VALUE: displayed text
 *      2) KEY: option group label; VALUE: another array with options
 *    <select ATT size="SIZE" multiple="multiple"*>
 *      <optgroup label="...">
 *        <option name="..." selected="selected"*>...</option>
 *      </optgroup>
 *    </select>
 *  
 * ATT: ATTRIBUTES name="NAME" id="form-NAME" class="error"*
 * VALUE: the current value
 */
function Dwoo_Plugin_formElement(Dwoo $dwoo, $name, $label = '',
	$description = '', $type = '', $attributes = array(), $multiple = false,
	$size = 1, $options = null, $leftColumn = true, $fullsize = false,
	$enabled = true)
{
	$error = $dwoo->scope['errors'][$name];
	$value = $dwoo->scope['values'][$name];
	$attr = "name=\"$name".($type == 'select' ? '[]' : '')."\" id=\"form-$name\" ".
		($error || $fullsize ? 'class="'.
		($error?'error ':'').($fullsize?'fullsize ':'').'"':'').
		(!$enabled ? "disabled=\"disabled\" " : '').
		implode(' ', array_map(function($k, $v) {
			return $k.'="'.htmlspecialchars($v).'"';
		}, array_keys($attributes), $attributes));
	
	switch ($type) {
		case 'textarea':
			$element = '<textarea '.$attr.'>'.htmlspecialchars($value).'</textarea>';
			break;
		case 'checkbox':
			$element =
				"<label for=\"form-$name\">".
				"<input $attr type=\"checkbox\"".
				($value ? ' checked="checked"' : '').' />'.$label.'</label>';
			if (is_string($leftColumn)) {
				$label = $leftColumn;
				$labelNoFor = true;
			} else
				$hideDT = true;
			break;
		case 'select':
			$element = "<select $attr size=\"$size\"".
				($multiple ? ' multiple="multiple" ' : '').'>'.
				Dwoo_Plugin_formElement__crawl($options, $value).
				"</select>";
			break;
		default:
			$element = "<input $attr type=\"$type\" value=\"".
				htmlspecialchars($value)."\" />";
	}
	
	if ($leftColumn) {
		$html = "<dl>";
		if (!$hideDT || !$label)
			$html .= "<dt><label".($labelNoFor? '' : " for=\"form-$name")."\">".
				Premanager\Execution\Translation::defaultGet('Premanager', 'label', 
				array('label' => $label))."</label></dt>";
		$html .= "<dd>";
	}
	$html .= $element.($description ? "<p>$description</p>" : '');
	if ($leftColumn) {
		if ($error)
			$html .= '<ul class="input-errors"><li>'.implode('</li><li>', $error).
				'</li></ul>';
		$html .= '</dd></dl>';
	}
	return $html;
}


function Dwoo_Plugin_formElement__crawl($arr, $values) {
	if (is_array($arr)) {
		foreach ($arr as $key => $value)
			if (is_array($value))
				$result .= '<optgroup label="'.htmlspecialchars($key).'">'.
					Dwoo_Plugin_formElement__crawl($value, $values).'</optgroup>';
			else
				$result .= '<option value="'.htmlspecialchars($key).'"'.
					($values[$key] ? ' selected="selected"' : '').'>'.
					htmlspecialchars($value).'</option>';
	}
	return $result;
}

?>
