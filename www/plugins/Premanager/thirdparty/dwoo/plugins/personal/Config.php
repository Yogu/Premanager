<?php

function Dwoo_Plugin_Config_compile(Dwoo_Compiler $compiler, $text)
{
	return 'Config::$'.trim($text, "'");
}
?>