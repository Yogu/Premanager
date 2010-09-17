<?php 
namespace Premanager\Models;

/**
 * Specifies the content type and other properties of a StructureNode
 */
class StructureNodeType {
	/**
	 * A structure node that only lists its subnodes
	 */
	const SIMPLE = 0;
	
	/**
	 * A structure node whose content is composed of panels
	 */
	const PANEL = 1;
	
	/**
	 * A structure node that is linked to a TreeClass
	 */
	const TREE = 2;
}

?>