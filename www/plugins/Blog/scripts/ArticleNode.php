<?php
if (!defined('PREMANAGER')) exit;

class Blog_ArticleNode extends Node {    
	public $article;                                                            

	// ===========================================================================
	
	public function __construct($parent, $article = null) {
		parent::__construct($parent);
		
		if ($article)
			$this->article = $article;
		else
			$this->article = new Blog_Article();	
	}

	// ===========================================================================   
                               
	// Finds a child of this node
	public function getChildByName($name) {	
	}   
	
	// Returns whether this node equals $node
	// returns bool
	public function equals($node) {
		return $node != null && get_class($node) == get_class($this) &&
			$this->article->getID() == $node->article->getID();
	}
	
	protected function doExecute() {
		if (Premanager::getValue('edit') !== null)
			$this->doEdit();
		else if (Premanager::getValue('delete') !== null)
			$this->doDelete();		  
		else if (Premanager::getValue('revisions') !== null)
			$this->doRevisions();		      
		else if (Premanager::getValue('publish') !== null)
			$this->doPublish();	        
		else if (Premanager::getValue('hide') !== null)
			$this->doHide();	
		else 
			$this->doView();
		
		return true;	
	}    
	
	// Returns translated name
	public function getName() {
		// Save name, because it can be modified on editing
		if ($this->storedName === null)
			$this->storedName = $this->article->getName();
		return $this->storedName;	 	
	}           
	
	// Returns translated title
	public function getTitle() {  
		// Save title, because it can be modified on editing
		if ($this->storedTitle === null)
			$this->storedTitle = $this->article->getTitle();
		return $this->storedTitle;	 	
	}     

	// ===========================================================================
	
	public function doView() {     
		$this->mode = 'view';
		
		if (Premanager::$me->hasRight('Blog', 'editArticles')) {
			$this->article->setSelectedRevision($this->article->getLastRevision());
					
			$rev = escape(Premanager::getValue('rev'));
			if ($rev == -1 && $this->article->getPublishedRevisionID()) {
				$this->article->setSelectedRevision(
					$this->article->getPublishedRevision());
			} else if ($rev) {
				$this->article->setSelectedRevision($rev);
			}
		} else {
			$this->article->setSelectedRevision(
				$this->article->getPublishedRevision());
		}

		if ($this->article->getSelectedRevision())
			$this->textHTML = PML::pmlToHTML($this->article->getText());

		$this->assignToOutput();
		Premanager::$output->select('Blog', 'articleView');
	}    

	public function doAdd() { 
		$this->mode = 'add';

		if(!Premanager::$me->hasRight('Blog', 'createArticles')) {
			Premanager::$output->selectAccessDenied();
			return;
		}
		
		$this->summary = Strings::get('Blog', 'autoSummaryArticleCreated');
		$this->editor = new Editor('Blog_ArticleNode', 'text');

		if (Premanager::postValue('Blog_ArticleNode_submit')) {
			if ($this->loadInput()) {
				$this->article->selectAvailableName();
				$this->article->insert();
				$this->article->insertRevision($this->text, $this->summary);
				Premanager::redirect($this->getURL());
			}
		} else if (Premanager::postValue('Blog_ArticleNode_preview')) {
			if ($this->loadInput()) {
				if ($this->text)
					$this->textHTML = PML::pmlToHTML($this->text);
				$this->preview = true;
			}
		}
                                             
		$this->editorContent = $this->editor->get();
		$this->assignToOutput();
		Premanager::$output->select('Blog', 'articleAdd');
	}    
	
	public function doEdit() {  
		$this->mode = 'edit';   

		if(!Premanager::$me->hasRight('Blog', 'editArticles')) {
			Premanager::$output->selectAccessDenied();
			return;
		}
		
		$this->editor = new Editor('Blog_Article', 'text');
		
		// Select Revision
		$rev = escape(Premanager::getValue('rev'));
		if ($rev == -1 && $this->article->getPublishedRevisionID())
			$this->article->setSelectedRevision(
				$this->article->getPublishedRevision());
		else if ($rev)
			$this->article->setSelectedRevision($rev);
		else          
			$this->article->setSelectedRevision($this->article->getLastRevision());
		
		if (Premanager::postValue('Blog_ArticleNode_submit')) {
			if ($this->loadInput()) {
				$this->article->selectAvailableName();
				$this->flush();
				$this->insertRevision($this->text, $this->summary);
				Premanager::redirect($this->getURL());
			}
		} else if (Premanager::postValue('Blog_Article_preview')) {
			if ($this->loadInput()) {
				if ($this->text)
					$this->textHTML = PML::pmlToHTML($this->text);
				$this->preview = true;
			}
		} else {
			$this->text = $this->article->getText();		
		}
		                
		$this->editorContent = $this->editor->get();
		$this->assignToOutput();
		Premanager::$output->select('Blog', 'articleEdit');
	}

	public function doRevisions() {
		$this->mode = 'revisions';    

		if(!Premanager::$me->hasRight('Blog', 'editArticles') &&
			!!Premanager::$me->hasRight('Blog', 'publishArticles')) {
			Premanager::$output->selectAccessDenied();
			return;
		}     
	
		$output = new Output();
		$output->select('Blog', 'articleRevisionsItem');
		Premanager::assignToOutput($output);
		$this->assignToOutput($output);
		
		$this->insertPagination($this->article->getRevisionCount(),
			$this->getURL().'?revisions');
		
		// We select from user because we have to translate the user but not the
		// revision
		$result = DataBase::query(
			"SELECT revision.revisionID, revision.revision, revision.summary, ".
				"UNIX_TIMESTAMP(revision.createTime) AS createTime, ".
				"LENGTH(revision.text) AS length, user.name AS creatorName, ".
				"user.color AS creatorColor, translation.title AS creatorTitle ".  
			"FROM ".table('Premanager_Users')." AS user ".
			"INNER JOIN ".table('Blog_Revisions')." AS revision ".
				"ON revision.creatorID = user.userID ",
			/* translator */ 'userID',
			"WHERE revision.articleID = '$this->id' ".
				"AND revision.languageID = '".Premanager::$language->id."' ".
			"ORDER BY revision.revision DESC ".
			"LIMIT $this->startIndex, $this->itemsPerPage");
		while ($result->next()) {
			$revision = new Module();                    
			$revision->revisionID = $result->value('revisionID');
			$revision->revision = $result->value('revision');
			$revision->summary = $result->value('summary');
			$revision->textLength = $result->value('length');
			$revision->creatorName = $result->value('creatorName');
			$revision->creatorColor = $result->value('creatorColor');
			$revision->creatorTitle = $result->value('creatorTitle');
			$revision->createTime = $result->value('createTime');
			
			$revision->assignToOutput($output, "Blog_Revision");
			$this->revisions .= $output->get();		
		} 
		
		$this->assignToOutput();
		Premanager::$output->select('Blog', 'articleRevisions');
	}     
	
	public function doPublish() {
		$this->mode = 'publish';    

		if(!Premanager::$me->hasRight('Blog', 'publishRevisions')) {
			Premanager::$output->selectAccessDenied();
			return;
		}
		
		if (!$this->article->setSelectedRevision(Premanager::getValue('rev'))) {
			Premanager::$output->selectInputError();
			return;		
		}
		
		if (Premanager::postValue('Confirmation_cancel')) {
			Premanager::redirect($this->getURL().'?rev='.$this->revision);
		} else if (Premanager::postValue('Confirmation_confirm')) {
			$this->publishedRevisionID = $this->revisionID;
			$this->article->publishRevision($this->article->getSelectedRevision());
			Premanager::redirect($this->getURL().'?rev=-1');
		} else {
			$this->assignToOutput();
			Premanager::$output->select('Blog', 'articleConfirmation');
			Premanager::$output->assign('message',
				Strings::get('Blog',
					$this->publishedRevisionID ? 'publishRevisionMessage' :
					'publishRevisionArticleHiddenMessage',
					array('revision' => $this->revision)));	
		}
	}     
	
	public function doHide() {
		$this->load();
		$this->mode = 'hide';    

		if(!Premanager::$me->hasRight('Blog', 'publishRevisions')) {
			Premanager::$output->selectAccessDenied();
			return;
		}
		
		if (!$this->publishedRevisionID) {
			Premanager::$output->selectInputError();
			return;
		}
		
		if (Premanager::postValue('Confirmation_cancel')) {
			Premanager::redirect($this->getURL());
		} else if (Premanager::postValue('Confirmation_confirm')) {
			$this->dbHide();
			Premanager::redirect($this->getURL());
		} else {
			$this->assignToOutput();
			Premanager::$output->select('Blog', 'articleConfirmation');
			Premanager::$output->assign('message',
				Strings::get('Blog', 'hideArticleMessage'));	
		}
	}   
	
	public function doDelete() {
		$this->load();
		$this->mode = 'delete';    

		if(!Premanager::$me->hasRight('Blog', 'deleteArticles')) {
			Premanager::$output->selectAccessDenied();
			return;
		}

		if (Premanager::postValue('Confirmation_cancel')) {
			Premanager::redirect($this->getURL());
		} else if (Premanager::postValue('Confirmation_confirm')) {
			$this->dbDelete();
			Premanager::redirect($this->parent->getURL());
		} else {
			$this->assignToOutput();
			Premanager::$output->select('Blog', 'articleConfirmation');
			Premanager::$output->assign('message',
				Strings::get('Blog', 'deleteArticleMessage'));	
		}
	}

	// ===========================================================================
	
	private function load() {
		if ($this->isEmpty)
			$this->update();					
	}
	
	private function update() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title, ".
				"translation.publishedRevisionID ".  
			"FROM ".table('Blog_Articles')." AS article ",
			/* translator */ 'articleID', 
			"WHERE article.articleID = '$this->id'");
		if ($result->next()) {
			$this->name = $result->value('name');
			$this->title = $result->value('title');
			$this->publishedRevisionID = $result->value('publishedRevisionID');
			
			$this->isEmpty = false;
		}                         	
	}
	
	private function getRevisionNumbers() {
		// Last revision
		$result = DataBase::query(
			"SELECT revision.revisionID, revision.revision, ". 
				"UNIX_TIMESTAMP(revision.createTime) AS createTime ".
			"FROM ".table('Blog_Revisions')." AS revision ".
			"WHERE revision.articleID = '$this->id' ".
				"AND revision.languageID = '".Premanager::$language->id."' ".
			"ORDER BY revision.revision DESC ".
			"LIMIT 0,1");                                  
		$this->lastRevisionID = $result->value('revisionID');
		$this->lastRevision = $result->value('revision');
		$this->lastRevisionTime = $result->value('createTime');
		
		// Published revision
		// id is already available from update()
		if ($this->publishedRevisionID) {
			$result = DataBase::query(
				"SELECT revision.revision, ". 
					"UNIX_TIMESTAMP(revision.createTime) AS createTime ".
				"FROM ".table('Blog_Revisions')." AS revision ".
				"WHERE revision.revisionID = '$this->publishedRevisionID'");
			$this->publishedRevision = $result->value('revision');
			$this->publishedRevisionTime = $result->value('createTime');
		}
	}
	
	protected function doLoadInput() {  
	  requires('PML.php');
	  
		// Title
		$this->formTitle = trim(Premanager::postValue('Blog_Article_title'));

		if (!$this->formTitle)
			$this->raiseInputError(Strings::get('Blog',
				'noArticleTitleInputtedError'), 'title');
				
		// Editor          
		if (!$this->editor->loadFromPOST(&$msg))
			$this->raiseInputError($msg, 'text');
		
		/*$this->text = trim(Premanager::postValue('Blog_Article_text'));

		if (!$this->text)
			$this->raiseInputError(Strings::get('Blog',
				'noArticleTextInputtedError'), 'text');
				
		if (!PML::validate($this->text, &$msg))
			$this->raiseInputError(Strings::get('Premanager',
				'invalidPMLInputtedError', array(message=>$msg)), 'text');*/
		
		// Summary
		$this->summary = trim(Premanager::postValue('Blog_Article_summary'));
	}

	// ===========================================================================         
	
	// Note: these data base functions do _not_ check if all data is correct
	
	// Inserts this object into data base
	public function dbInsert() {
		$this->dbInsertBase('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			array(),
			array(
				'name' => $this->name,
				'title' => $this->title,
				'publishedRevisionID' => 0)
		);
		
		// Insert revision
		$this->dbInsertRevision();
	}
	
	// Deletes this object from data base
	public function dbDelete() {
		$this->dbDeleteBase('Blog_Articles', 'articleID');        
			    
		DataBase::query(
			"DELETE FROM ".table('Blog_Revisions')." ".
			"WHERE articleID = '$this->id'");
	}

	// Updates data base items of this object
	public function dbUpdate() {
		$this->dbUpdateBase('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			array(),
			array(
				'name' => $this->name,
				'title' => $this->title)
		);
	}     
	
	// Inserts a new revision based on text field and current language
	public function dbInsertRevision() {
		// Get highest revision number
		$result = DataBase::query(
			"SELECT MAX(revision.revision) AS revision ".
			"FROM ".table('Blog_Revisions')." AS revision ".
			"WHERE revision.articleID = '$this->id' ".
				"AND revision.languageID = '".Premanager::$language->id."'");
		$revision = $result->value('revision')+1;
		
		$text = escape($this->text);
		$summary = escape($this->summary);
		$me = Premanager::$me->id;
		$ip = Client::$ip;
		
		// Update editor fields{
		$this->dbUpdateBase('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			array(),
			array()
		);
	
		DataBase::query(
			"INSERT INTO ".table('Blog_Revisions')." ".
			"(articleID, languageID, revision, text, summary, createTime, ".
				"creatorID, creatorIP) ".
			"VALUES ('$this->id', '".Premanager::$language->id."', '$revision', ".
				"'$text', '$summary', NOW(), '$me', '$ip')");	
	}   
	
	// Publishes a specified revision
	public function dbPublishRevision() {
		$this->dbUpdateBase('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			array(),
			array(
				'publishedRevisionID' => $this->publishedRevisionID)
		);
	}       
	
	// Publishes no revision
	public function dbHide() {
		$this->dbUpdateBase('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			array(),
			array(
				'publishedRevisionID' => 0)
		);
	}   

	// Returns true, if this.name is available, otherwise returns false
	// string name: name to be checked
	// set flags: set of
	//   IGNORE_THIS: names of this.id has to be ignored	
	public function isNameAvailable($name, $flags = 0) {
		return $this->isNameAvailableBase('Premanager_Projects', 'projectID',
			$flags | UNTRANSLATED_NAME, $name);
	}
}

?>
