<?php

function Dwoo_Plugin_shortRelativeDate(Dwoo $dwoo, $time)
{
  return Premanager::$language->formatTime($time, 'shortRelative');
}
?>