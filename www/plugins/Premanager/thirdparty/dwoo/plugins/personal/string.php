<?php

function Dwoo_Plugin_string(Dwoo $dwoo, $plugin, $string, $params = array())
{
  return Premanager\Execution\Translation::defaultGet($plugin, $string,
  	$params === null ? array() : $params);
}
?>