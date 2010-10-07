<?php
if (!defined('PREMANAGER')) exit;

/**
 * An article in the blog
 *
 * @property-read int $id the id of this article
 * @property string $name the translated url name of this article
 * @property string $title the translated title
 * @property Blog_Revision $publishedRevision the published revision
 * @property Blog_Revision $lastRevision the latest added revision
 * @property int $revisionCount count of all revisions in this article
 */
class Blog_Article extends Module {
	// Data Base ID
	private $_id;
	
	// URL name
	private $_name;
	
	// Translated title
	private $_title;

	// false means not received; null means not available / not set 
	private $_publishedRevision = false;
	private $_publishedRevisionID;
	private $_lastRevision = false;
	private $_revisionCount = -1;
	private $_isTranslatedIntoCurrentLanguae;
	
	// Properties
	public $id = PROPERTY_GET;
	public $name = PROPERTY_GET_SET;
	public $title = PROPERTY_GET_SET;
	public $publishedRevision = PROPERTY_GET;
	public $lastRevision = PROPERTY_GET;
	public $revisionCount = PROPERTY_GET;
	
	// ===========================================================================
	
	/**
	 * Creates an article that exists already in data base
	 *
	 * Make sure that there is an article with the id $id which is not deleted yet
	 * and is not going to be deleted while this object exists. Ensure that
	 * $name and $title parameter are correct if they are not null
	 *
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $title
	 */
	public function __construct($id, $name = null, $title = null) {
		parent::__construct();
		
		if ($name !== null)
			$name = (string) $name;
		if ($title !== null)
			$title = (string) $title;
		
		if (!is_int($id) || $id < 0)
			throw new InvalidArgumentException(
				'$id must be a positive integer value');
		
		$this->_id = $id;
		$this->_name = $name;
		$this->_title = $title;	
	}    

	// ===========================================================================   
                               
	/**
	 * Creates an article that exists already in data base, using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of article
	 * @return Blog_Article  
	 */
	public static function createFromName($name) {
		$result = DataBase::query(
			"SELECT name.articleID ".            
			"FROM ".table('Blog_ArticlesName')." AS name ".
			"WHERE name.name = '".escape(Strings::unitize($name)."'"));
		if ($result->next()) {
			$article = new Blog_Article($result->value('articleID'));
			return $article;
		}
		return null;
	}
	
	/**
	 * Creates a new article and inserts it into data base
	 *
	 * @param $name article's name
	 * @param $title article's title
	 * @param $text text of first revision
	 * @param $summary summary for first revision
	 * @return Blog_Article
	 */
	public static function createNew($name, $title, $text, $summary) {
		$name = trim($name);
		$title = trim($title);
		$text = trim($text);
		$summary = trim($summary);

		if (!$name)
			throw new InvalidArgumentException(
				'$name is an empty string or contains only whitespaces');   
		if (!$title)
			throw new InvalidArgumentException(
				'$title is an empty string or contains only whitespaces');   
		if (!$text)
			throw new InvalidArgumentException(
				'$text is an empty string or contains only whitespaces');
	
		$id = DataBaseHelper::insert('Blog_Article', 'articleID', 
			CREATOR_FIELDS | EDITOR_FIELDS, $name, 
			array(), 
			array(
				'title' => $title,
				'publishedRevisionID' => 0)
		);
		$article = new Blog_Article($id, $name, $title);
		$article->insertRevision($text, $summary);
		return $article;	
	}      

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to an article.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public static function staticIsNameAvailable($name) {    	
		return DataBaseHelper::isNameAvailable('Blog_Articles', 'articleID',
			(string) $name);
	}
			

	// ===========================================================================
	
	/**
	 * Gets the id of this article
	 *
	 * @return int
	 */
	public function getID() {
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
	
		return $this->_id;
	}

	/**
	 * Gets the name of this article
	 *
	 * @return string
	 */
	public function getName() {
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if ($this->_name === null)
			$this->load();
		return $this->_name;	
	}  
	
	/**
	 * Gets the title of this article
	 *
	 * @return string
	 */
	public function getTitle() {    
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if ($this->_title === null)
		$this->load();
		return $this->_title;	
	}      
	
	/**
	 * Gets the published revision
	 *
	 * If this article is not translated into current translation, returns null by
	 * default. Set $tolerant to true if you want to get returned the published
	 * revision of the same language as $title and $name properties, if the
	 * article is not translated into the current language.  
	 *
	 * @param bool $tolerant if true, may return the published revision of another
	 *   language  
	 *
	 * @return Blog_Revision the revision or null, if article is hidden
	 */
	public function getPublishedRevision($tolerant = false) {   
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if ($this->_publishedRevision === false) {
			if ($this->_publishedRevisionID === null)
				$this->load();     
				
			if (!$this->_isTranslatedIntoCurrentLanguage && !$tolerant)
				return null;
				
			if ($this->_publishedRevisionID)
				$this->_publishedRevision =
					new Blog_Revision($this->publishedRevisionID, $this);
			else
				$this->_publishedRevision = null;		
		}
		if (!$this->_isTranslatedIntoCurrentLanguage && !$tolerant)
			return null;
		else
			return $this->_publishedRevision;	
	}         
	
	/**
	 * Gets the newest revision
	 *
	 * @return Blog_Revision the revision or null, if there are no revisions
	 */
	public function getLastRevision() {  
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if ($this->_lastRevision === false) {
			$result = DataBase::query(
				"SELECT revision.revisionID, revision.revision, ". 
					"UNIX_TIMESTAMP(revision.createTime) AS createTime ".
				"FROM ".table('Blog_Revisions')." AS revision ".
				"WHERE revision.articleID = '$this->_id' ".
					"AND revision.languageID = '".Premanager::$language->id."' ".
				"ORDER BY revision.revision DESC ".
				"LIMIT 0,1");   
			if ($result->next())
				$this->_lastRevision = new Blog_Revision($result->value('revisionID'),
					$this, $result->value('revision'), $result->value('createTime'));
			else
				$this->_lastRevision = null;		
		}
		return $this->_lastRevision;	
	}     
	
	/**
	 * Gets the count of revisions in this article
	 *
	 * @return int count of revisions
	 */
	public function getRevisionCount() { 
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if ($this->_revisionCount === null) {
			$result = DataBase::query(
				"SELECT COUNT(revision.revisionID) AS count ".
				"FROM ".table('Blog_Revisions')." AS revision ".
				"WHERE revision.articleID = '$this->_id' ".
					"AND revision.languageID = '".Premanager::$language->id."'");
			$this->_revisionCount = $result->value('count');
		}
		return $this->_revisionCount;
	}
	
	/**
	 * Gets a revision by number
	 *
	 * @param int $number the revision number of the revision
	 * @return Blog_Revision 
	 */
	public function getRevision($number) {   
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		if (!is_int($number) || $number < 1)
			throw new InvalidArgumentException(
				'$number must be a positive integer value'); 
	
		if ($this->_publishedRevision &&	
			$this->_publishedRevision->number == $number)
			return $this->publishedRevision;
		else if ($this->_lastRevision &&
			$this->_lastRevision->number == $number)
			return $this->lastrevision;
		else
			return Revision::createFromNumber($this, $number);			
	}   
	
	public function insertRevision($text, $summary) {   
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		$text = trim($text);
		$summary = trim($summary);
		if (!$text)                                            
			throw new InvalidArgumentException(
				'$text is empty or contains only whitespaces');   
		if (!$summary)                                            
			throw new InvalidArgumentException(
				'$summary is empty or contains only whitespaces');
			
		$me = Premanager::$me->id;
		$ip = Config::$ip;
		$rev = $this->lastRevision->number+1;
		DataBase::query(
			"INSERT INTO ".table('Blog_Revisions')." ".
			"(articleID, languageID, revision, text, summary, createTime, ".
				"creatorID, creatorIP) ".
			"VALUES ('".$this->id."', '".Premanager::$language->id."', ".
				"'$rev', ".
				"'".escape($text)."', '".escape($summary)."', NOW(), '$me', '$ip')");
		$revisionID = DataBase::insertID();		
		    
		// Update editor fields
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			$this->id, $this->name, array(), array());
		
		$this->_lastRevision = new Revision($revisionID, $this, $rev, time());
		return $this->_lastRevision;
	}
	
	/**
	 * Deletes this article
	 *
	 * This object will afterwards "seem to be deleted", its methods will not 
	 * work. Make sure that there are no other instances of Blog_Article 
	 * containing this deleted object, because they will not be notified.
	 */
	public function delete() {         
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		DataBaseHelper::delete('Blog_Articles', 'articleID', 0, $this->_id);
		
		// Delete revisions
		DataBase::query(
			"DELETE FROM ".table('Blog_Revisions')." ".
			"WHERE articleID = '$this->_id'");
			
		$this->_id = null;
	}
	
	/**
	 * Changes $name and $title property
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param $name new value for $name property
	 * @param $title new value for $title property
	 */
	public function setValues($name, $title) {     
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');     
			
		$name = trim($name);
		$title = trim($title);
		
		if (!$name)
			throw new InvalidArgumentException(
				'$name is an empty string or contains only whitespaces');   
		if (!$title)
			throw new InvalidArgumentException(
				'$title is an empty string or contains only whitespaces'); 
			
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS, $this->_id, $name,
			array(),
			array(
				'name' => $name,
				'title' => $title)
		);
		$this->_name = $name;
		$this->_title = $title;
	}     
	
	/**
	 * Changes $name property
	 * 
	 * This value will be changed in data base and in this object.
	 *
	 * @param $name new value for $name property
	 */
	public function setName($name) { 
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');   
			
		$name = trim($name);
		if (!$name)
			throw new InvalidArgumentException(
				'$name is an empty string or contains only whitespaces');  
			
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS, $this->_id, $name,
			array(),
			array(
				'name' => $name)
		);
		$this->_name = $name;
	}           
	
	/**
	 * Changes $title property
	 * 
	 * This value will be changed in data base and in this object.
	 *
	 * @param $name new value for $name property
	 */
	public function setTitle($title) { 
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');  
			
		$title = trim($title);
		if (!$title)
			throw new InvalidArgumentException(
				'$title is an empty string or contains only whitespaces');  
			
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS, $this->_id, $this->name,
			array(),
			array(
				'title' => $title)
		);
		$this->_title = $title;
	}
	
	/**
	 * Hides this article from guests
	 *
	 * Changes $publishedRevision property to null and hides this article from
	 * guests
	 */
	public function hide() {    
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			$this->_id, $this->name,
			array(),
			array('publishedRevisionID' => 0)
		);
		$this->publishedRevisionID = 0;
		$this->publishedRevision = null;
	}     
	
	/**
	 * An internal method
	 *
	 * This method should not be called explicitely.
	 *
	 * It is called by a Blog_Revision when that is published to update the
	 * $publishedRevision property. This works automatically.
	 *
	 * @param Blog_Revision $revision the new published revision
	 */
	public function internalSetPublishedRevision(Blog_Revision $revision) {    
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			
		$this->_publishedRevision = $revision;
		$this->_publishedRevisionID = $revision->id; 	
	}

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to an article. This article's
	 * names are excluded, they are available.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name) {   
		if ($this->_id === null)
			throw new InvalidOperationException('Article is deleted');
			 	
		DataBaseHelper::isNameAvailable('Blog_Articles', 'articleID',
			IGNORE_THIS, (string) $name, $this->_id);
	}  

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title, ".
				"translation.publishedRevisionID, translation.languageID ".  
			"FROM ".table('Blog_Articles')." AS article ",
			/* translator */ 'articleID', 
			"WHERE article.articleID = '$this->_id'");
		if ($result->next()) {
			$this->_name = $result->value('name');
			$this->_title = $result->value('title');
			$this->_publishedRevisionID = $result->value('publishedRevisionID');
			$this->_isTranslatedIntoCurrentLanguage = 
				$result->value('languageID') == Premanager::$language->id;
		} else
			throw new UnexpectedStateException('Invalid article id found');
	}
}

?>
