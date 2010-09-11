<?php

function Dwoo_Plugin_htmlstring(Dwoo $dwoo, $plugin, $string, $params = null)
{
  return htmlspecialchars(Strings::get($plugin, $string, $params));
}
?>