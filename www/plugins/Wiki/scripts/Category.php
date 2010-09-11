<?php
if (!defined('PREMANAGER')) exit;

class Wiki_Category extends Node {
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
		// Look for child category
		$result = DataBase::query(
			"SELECT name.categoryID ".
			"FROM ".table('Wiki_CategoriesName')." name ".
			"INNER JOIN ".table('Wiki_Categories')." category ".
			"WHERE name.name = '".escape(Strings::unitize($name)."' ".
				"AND category.parentID = '$this->id' ".
				"AND name.categoryID != '0'"));
		if ($result->next()) {
			$category = new Wiki_Category($this);
			$category->id = $result->value('categoryID');   
			return $category;
		}
		
		// Look for article    
		$result = DataBase::query(
			"SELECT name.articleID ".
			"FROM ".table('Wiki_ArticlesName')." name ".
			"INNER JOIN ".table('Wiki_Articles')." article ".
				"ON article.articleID = name.articleID ".
			"WHERE name.name = '".escape(Strings::unitize($name)."' ".
				"AND article.categoryID = '$this->id'"));
		if ($result->next()) {
			require_once('Article.php');
			$article = new Wiki_Article($this);
			$article->id = $result->value('articleID');
			return $article;
		}
	}        
	
	// Lists $count child nodes around a reference node
	// Node referenceNode = null: If null, list starts at first item; if not null,
	//   $referenceNode is in the center of returned list
	// int count = -1: If -1, all all items are returned; otherwise, $count items
	//   are returned 
 	// returns array(Node)
	public function listChildren($referenceNode = null, $count = -1) {
		$list = array();
		
		$result = DataBase::query(
			"SELECT category.categoryID, translation.name, translation.title ".
			"FROM ".table('Wiki_Categories')." AS category ",
			/* translator */ 'categoryID',
			"WHERE category.categoryID != '$this->id' ".
				"AND category.parentID = '$this->id' ".
			"ORDER BY translation.title");
		while ($result->next()) {      
			$child = new Wiki_Category($this);
			$child->id = $result->value('categoryID');
			$child->parentID = $this->id;
			$child->title = $result->value('title');
			$child->name = $result->value('name');
			$child->isEmpty = false;
			$list[] = $child;
		}   
		
		$result = DataBase::query(
			"SELECT article.articleID, translation.name, translation.title ".
			"FROM ".table('Wiki_Articles')." AS article ",
			/* translator */ 'articleID',
			"WHERE article.categoryID = '$this->id' ".
			"ORDER BY translation.title");
		while ($result->next()) {       
			require_once('Article.php');
			
			$child = new Wiki_Article($this);
			$child->id = $result->value('articleID');
			$child->categoryID = $this->id;
			$child->title = $result->value('title');
			$child->name = $result->value('name');
			$child->isEmpty = false;
			$list[] = $child;
		}

		return $list;
	}
	
	protected function doExecute() {
		if (Premanager::getValue('edit') !== null) {
		
		} else if (Premanager::getValue('delete') !== null) {     
		
		} else if (Premanager::getValue('add') !== null) {
		
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
			"SELECT category.indexArticleID ".  
			"FROM ".table('Wiki_Categories')." AS category ".
			"WHERE category.categoryID = '".$this->id."'");
		$this->indexArticleID = $result->value('indexArticleID');

		$this->assignToOutput();
		Premanager::$output->select('Wiki', 'categoryView');
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
			"FROM ".table('Wiki_Categories')." AS category ",
			/* translator */ 'categoryID',
			"WHERE category.categoryID = '".$this->id."'");
		if ($result->next()) {
			$this->name = $result->value('name');
			$this->title = $result->value('title');
			
			$this->isEmpty = false;
		}                      	
	}  
}

?>
