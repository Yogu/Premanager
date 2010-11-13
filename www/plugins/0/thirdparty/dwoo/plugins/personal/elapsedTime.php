<?php

function Dwoo_Plugin_elapsedTime_compile(Dwoo_Compiler $compiler) {
  return "floor((microtime(true) - REQUEST_TIME) * 1000) . ' ms'";
}

?>
