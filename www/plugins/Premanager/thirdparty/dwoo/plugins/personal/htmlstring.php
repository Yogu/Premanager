<?php

function Dwoo_Plugin_htmlstring(Dwoo $dwoo, $plugin, $string, $params = array())
{
  return htmlspecialchars(Premanager\Execution\Translation::defaultGet($plugin,
  	$string,$params === null ? array() : $params));
}
?>