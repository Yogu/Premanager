<?php
if (!defined('PREMANAGER')) exit;

class Wiki_Wiki extends TreeNode {
	public $list;      

	// ===========================================================================   
 
	// Finds a child of this node
	public function getChildByName($name) {
		$result = DataBase::query(
			"SELECT name.categoryID ".
			"FROM ".table('Wiki_CategoriesName')." AS name ".
			"WHERE name.name = '".escape(Strings::unitize($name)."'"));
		if ($result->next()) {
			require_once('Category.php');
			$category = new Wiki_Category($this);
			$category->id = $result->value('categoryID');
			return $category;
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
			require_once('Category.php');
		
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
	        
	// Prepares output and selects template
	protected function doExecute() {   
	
	}        
}

?>
