<?php

function Dwoo_Plugin_toolBarItem_compile(Dwoo_Compiler $compiler, $url, $title,
	$description, $iconURL = '', $enabled = true, $method='GET', $postName = '')
{
	$descriptionCode = "($description ? ' title=\"'.htmlspecialchars(".
		"$description).'\"' : '')";
	$iconCode = "($iconURL ? ' style=\"background-image: url('.".
		"htmlspecialchars(Premanager\IO\Config::getStaticURLPrefix().$iconURL).".
		"') !important;\"' : '')";
	
	switch ($method) {
		case "'POST'":
			return "'<li><form action=\"./'.htmlspecialchars($url).'\" ".
				"method=\"POST\"><input type=\"submit\" '.".
				"($postName ? 'name=\"'.htmlspecialchars($postName).'\" ' : '').".
				"(!$enabled ? 'disabled=\"disabled\" ' : '').".
				"'value=\"'.htmlspecialchars($title).'\"'.$descriptionCode.$iconCode.".
				"' /></form></li>'";
		default:
			return "'<li>'.($enabled ? '<a href=\"./'.htmlspecialchars($url).'\" : ".
				"'<span class=\"disabled\"').$descriptionCode.$iconCode.'>'.".
				"htmlspecialchars($title).'</'.($enabled ? 'a' : 'span').'></li>'";
	}
}
?>