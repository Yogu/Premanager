<?php

function Dwoo_Plugin_Premanager_compile(Dwoo_Compiler $compiler, $text)
{
	return 'Premanager::$'.trim($text, "'");
}
?>