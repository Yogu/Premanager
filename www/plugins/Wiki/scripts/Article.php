<?php
if (!defined('PREMANAGER')) exit;

class Wiki_Article extends Node {
	// Data base id
	public $id;
	
	// Translated name
	public $name;
	
	// Translated title
	public $title;
	
	// Specifies whether data has been received from data base (false) or not
	// (true)
	public $isEmpty = true;

	// ===========================================================================   
                               
	// Finds a child of this node
	public function getChildByName($name) {
	}
	
	protected function doExecute() {
		if (Premanager::getValue('edit') !== null) {
		
		} else if (Premanager::getValue('delete') !== null) {   
		
		} else {
			$this->doView();		
		}			
		
		return true;	
	}    
	
	// Returns translated name
	public function getName() {
		$this->load();
		return $this->name;	 	
	}           
	
	// Returns translated title
	public function getTitle() {
		$this->load();
		return $this->title;
	}     

	// ===========================================================================  
	
	// View user
	private function doView() {
		$this->load();  
	
		// Load additional data
		$result = DataBase::query(
			"SELECT translation.publicRevisionID ".  
			"FROM ".table('Wiki_Articles')." AS article ",
			/* translator */ 'articleID',			
			"WHERE article.articleID = '".$this->id."'");
		$id = $result->value('publicRevisionID');
		$result = DataBase::query(
			"SELECT revision.text ".
			"FROM ".table('Wiki_Revisions')." AS revision ".
			"WHERE revision.revisionID = '$id'");
		$this->text = $result->value('text');

		$this->assignToOutput();
		Premanager::$output->select('Wiki', 'articleView');
		Premanager::$output->exists = true;	
	}   

	// ===========================================================================  
	
	private function load() {
		if ($this->isEmpty)
			$this->update();					
	}
	
	private function update() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title ".  
			"FROM ".table('Wiki_Articles')." AS article ",
			/* translator */ 'articleID',
			"WHERE article.articleID = '".$this->id."'");
		if ($result->next()) {
			$this->name = $result->value('name');
			$this->title = $result->value('title');
			
			$this->isEmpty = false;
		}                      	
	}  
}

?>
