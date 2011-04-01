<?php

function Dwoo_Plugin_urlPrefix_compile(Dwoo_Compiler $compiler, $language,
	$edition)
{
  return "Premanager\URL::fromTemplateUsingStrings(".
  	"$language !== null ? $language : ".
  		"Premanager\Execution\Environment::getCurrent()->getLanguage()->getName(), ".
  	"$edition !== null ? $edition : ".
  		"(Premanager\Execution\Environment::getCurrent()->getEdition() == ".
  		"Premanager\Execution\Edition::PRINTABLE ? 'print' : ".
  		"(Premanager\Execution\Environment::getCurrent()->getEdition() == ".
  		"Premanager\Execution\Edition::MOBILE ? 'mobile' : '')))";
}
?>