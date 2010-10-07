<?php
if (!defined('PREMANAGER')) exit;

class Blog_ArticlesNodr extends TreeNode {
	public $list;      

	// ===========================================================================   
 
	// Finds a child of this node
	public function getChildByName($name) {
		$result = DataBase::query(
			"SELECT name.articleID ".  
			"FROM ".table('Blog_ArticlesName')." AS name ".
			"WHERE name.name = '".escape(Strings::unitize($name)."'"));
		if ($result->next()) {
			require_once('Article.php');

			$article = new Blog_Article($this);
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
		if ($referenceNode != null && $count >= 0) {
			$result = DataBase::query(
				"SELECT COUNT(article.articleID) AS count ".
				"FROM ".table('Blog_Articles')." AS article ",
				/* translator */ 'articleID',
				"WHERE LOWER(translation.title) < '".
					escape(Strings::unitize($referenceNode->getTitle())."' ".
				(!Premanager::$me->hasRight('Blog', 'editArticles') ?
					"AND translation.publishedRevisionID != 0 " : '')));
			$startIndex = max($result->value('count') - floor(($count-1) / 2), 0); 		
		} else
			$startIndex = 0;
			
		$result = DataBase::query(
			"SELECT article.articleID, translation.name, translation.title ".  
			"FROM ".table('Blog_Articles')." AS article ",
			/* translator */ 'articleID', 
			(!Premanager::$me->hasRight('Blog', 'editArticles') ?
				"WHERE translation.publishedRevisionID != 0 " : '').
			"ORDER BY LOWER(translation.name) ASC ". 
			($count >= 0 ? "LIMIT $startIndex, $count" : ''));
		$list = array();
		while ($result->next()) {
			require_once('Article.php');

			$article = new Blog_Article($this);
			$article->id = $result->value('articleID');
			$article->name = $result->value('name');   
			$article->title = $result->value('title');
			$article->isEmpty = false;
			$list[] = $article;
		}
		
		return $list;
	}
	        
	// Prepares output and selects template
	protected function doExecute() {   
		if (Premanager::getValue('add') !== null)
			$this->doAdd();
		else
			$this->doList();

		return true;	
	}   
	    
	// Returns item count
	protected function getItemCount() {
		$result = DataBase::query(
			"SELECT COUNT(article.articleID) AS count ".
			"FROM ".table('Blog_Articles')." AS article ",
			/* translator */ 'articleID',
			(!Premanager::$me->hasRight('Blog', 'editArticles') ?
				"WHERE translation.publishedRevisionID != 0 " : ''));
		return $result->value('count');	
	}     

	// ===========================================================================

	public function doList() {
		require_once('Article.php');
		$this->insertPagination();
	
		$output = new Output();
		$output->select('Blog', 'articleListItem');
		Premanager::assignToOutput($output);
		$article = new Blog_Article($this);   

		$result = DataBase::query(
			"SELECT article.articleID, translation.name, translation.title ".
			"FROM ".table('Blog_Articles')." AS article ",
			/* translator */ 'articleID',
			(!Premanager::$me->hasRight('Blog', 'editArticles') ?
				"WHERE translation.publishedRevisionID != 0 " : '').
			"ORDER BY article.createTime DESC ".
			"LIMIT $this->startIndex, $this->itemsPerPage");
		$this->list = '';
		while ($result->next()) {
			$article->id = $result->value('articleID');
			$article->name = $result->value('name');
			$article->title = $result->value('title');
			
			$article->assignToOutput($output);
			$this->list .= $output->get();		
		}
		
		$this->assignToOutput();
		Premanager::$output->select('Blog', 'articleList');
	}        
	
	public function doAdd() {  
		require_once('Article.php');
		
		$article = new Blog_Article($this);
		$article->doAdd();
	}
}

?>
