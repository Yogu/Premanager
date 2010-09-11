<?php

function Dwoo_Plugin_string(Dwoo $dwoo, $plugin, $string, $params = null)
{
  return Strings::get($plugin, $string, $params);
}
?>