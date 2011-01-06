<?php

function Dwoo_Plugin_longDateTime(Dwoo $dwoo, $time)
{
  return Premanager\Execution\Environment::getCurrent()->getTranslation()-> formatTime($time);
}
?>