<?php

function Dwoo_Plugin_longDateTime(Dwoo $dwoo, $time)
{
  return Premanager::$language->formatTime($time);
}
?>