<?php

function Dwoo_Plugin_concat_compile(Dwoo_Compiler $compiler, $value1, $value2)
{
  return "$value1.$value2";
}
?>