<?php
namespace Premanager\Styles;

use Premanager\Execution\StylesheetInfo;
use Premanager\Execution\Edition;
use Premanager\Execution\Style;
use Premanager\Execution\Environment;

/**
 * The classic style
 */
class ClassicStyle extends Style {
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Gets an array of stylesheets this style uses in the current environment
	 * 
	 * @return array of Premanager\Execution\StylesheetInfo
	 */
	public function getStylesheets() {
		switch (Environment::getCurrent()->getedition()) {
			case Edition::COMMON:
				return array(
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/reset.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/general.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/classes.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/layout.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/print.css', 'print'));
					
			case Edition::PRINTABLE:
				return array(
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/reset.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/general.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/classes.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/layout.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/print.css'));
					
			case Edition::MOBILE:
				return array(
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/reset.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/general.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/classes.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/layout.css'),
					StylesheetInfo::simpleCreate('Premanager',
						'styles/classic/stylesheets/print.css', 'print'));
		}
	}
}

?>