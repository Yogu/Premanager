<?php

function Dwoo_Plugin_Client_compile(Dwoo_Compiler $compiler, $text)
{
	return 'Client::$'.trim($text, "'");
}
?>