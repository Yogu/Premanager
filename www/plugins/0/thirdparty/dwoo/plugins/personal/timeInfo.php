<?php

function Dwoo_Plugin_timeInfo_compile(Dwoo_Compiler $compiler, $onlyTotal = false) {
	return $onlyTotal . '?' .
		"floor((microtime(true) - REQUEST_TIME) * 1000)" .
		':'.
  	"floor((microtime(true) - REQUEST_TIME) * 1000) . ' ms (DB: ' . floor(Premanager\IO\DataBase\DataBase::getQueryTime()*100) . ' ms for ' . Premanager\IO\DataBase\DataBase::getQueryCount() . ' queries)'";
}

?>
