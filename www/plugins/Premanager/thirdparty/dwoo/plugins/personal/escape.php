<?php

function Dwoo_Plugin_escape_compile(Dwoo_Compiler $compiler, $text)
{
  return "mysql_real_escape_string($text)";
}
?>