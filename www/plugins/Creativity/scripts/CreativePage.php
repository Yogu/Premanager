<?php
if (!defined('PREMANAGER')) exit;

class Creativity_CreativePage extends TreeNode {
	public $list;      

	// ===========================================================================   
 
	// Finds a child of this node
	public function getChildByName($name) {
	}
	        
	// Prepares output and selects template
	protected function doExecute() {   
		if (Premanager::getValue('edit') !== null) {
		
		} else {
			$this->doView();		
		}

		return true;	
	}     

	// ===========================================================================

	// View Creative Page
	public function doView() {
		$result = DataBase::query(
			"SELECT translation.text ".
			"FROM ".table('Creativity_CreativePages')." AS page ",
			/* translator */ 'pageID ',			
			"WHERE page.nodeID = '".$this->id."'");
		$this->text = $result->value('text');
	
		$this->assignToOutput();
		Premanager::$output->select('Creativity', 'creativePage');
		Premanager::$output->exists = true;
	}    
}

?>
