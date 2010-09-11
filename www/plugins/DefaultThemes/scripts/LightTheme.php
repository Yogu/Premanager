<?php
if (!defined('PREMANAGER')) exit;

class DefaultThemes_LightTheme extends Theme {
	public function doCommon() {
		$this->addStylesheet('light/styles/common.css');
		$this->addStylesheet('light/styles/print.css', null, 'print');		
	}
	
	public function doPrintable() {
		$this->addStylesheet('light/styles/common.css');
		$this->addStylesheet('light/styles/print.css');
	}
	
	public function doMobile() {
		$this->addStylesheet('light/styles/common.css');
		$this->addStylesheet('light/styles/mobile.css');
	}
}

?>