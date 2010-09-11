<?php
if (!defined('PREMANAGER')) exit;

class DefaultThemes_ClassicTheme extends Theme {
	public function doCommon() {
		$this->addStylesheet('classic/styles/common.css');
		$this->addStylesheet('classic/styles/print.css', null, 'print');		
	}
	
	public function doPrintable() {
		$this->addStylesheet('classic/styles/common.css');
		$this->addStylesheet('classic/styles/print.css');
	}
	
	public function doMobile() {
		$this->addStylesheet('classic/styles/common.css');
		$this->addStylesheet('classic/styles/mobile.css');
	}
}

?>