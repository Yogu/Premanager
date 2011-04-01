<?php

function Dwoo_Plugin_escape_compile(Dwoo_Compiler $compiler, $text = 'mt_getrandmax')
{
  return "hash('sha256',$text)";
}
?>