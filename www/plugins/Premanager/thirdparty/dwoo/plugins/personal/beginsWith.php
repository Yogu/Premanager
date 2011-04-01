<?php

function Dwoo_Plugin_beginsWith_compile(Dwoo_Compiler $compiler, $text, $beginning) {	
  return "substr($text, 0, strlen($beginning)) == $beginning";
}
?>