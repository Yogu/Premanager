<?php

namespace Premanager\Modeling;

/**
 * Provides flags for model descriptors
 */
class ModelFlags {
	/**
	 * Specifies that a model has creator and createTime fields 
	 */
	const CREATOR_FIELDS = 0x01;
	
	/**
	 * Specifies that a model has editor, editTime and editTimes fields
	 */
	const EDITOR_FIELDS = 0x02;
	
	/**
	 * Specifies that a model has translated fields
	 */
	const HAS_TRANSLATION = 0x04;
	
	/**
	 * Specifies that the model has an untranslated name field
	 */
	const UNTRANSLATED_NAME = 0x08;
	
	/**
	 * Specifies that the model has a translated name field
	 */
	const TRANSLATED_NAME = 0x10;
	
	/**
	 * Specifies that a model does not have a name field
	 */
	const NO_NAME = 0x20;
}

?>
