<?php

function Dwoo_Plugin_longRelativeTime(Dwoo $dwoo, $time)
{
  return Premanager::$language->formatTime($time, 'longRelative');
}
?>