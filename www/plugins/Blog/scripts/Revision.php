<?php
if (!defined('PREMANAGER')) exit;

/**
 * A single revision of a blog article    
 *
 * @property-read int $id the id of this revision  
 * @property-read Blog_Article $article the article that contains this revision
 * @property int $number the number of this revision
 * @property int $createTime the time this revision has been created
 * @property string $text the text of this revision 
 */
class Blog_Revision extends Module {
	// Data Base ID
	private $_id;

	// Reference to Blog_Article
	private $_article;
	
	// If $article is at the constructor set, this property normally is not set
	private $_articleID;

	// Revision number
	private $_number;

	private $_createTime;
	private $_text;
	
	// Properties
	public $id = PROPERTY_GET;
	public $aritlce = PROPERTY_GET;
	public $number = PROPERTY_GET;
	public $createTime = PROPERTY_GET;
	public $text = PROPERTY_GET;

	// ===========================================================================
	
	/**
	 * Creates a revision that exists already in data base
	 *
	 * @param int $id
	 * @param Blog_Article $article optional
	 * @param int $number optional
	 * @param int $createTime optional
	 */
	public function __construct($id, Blog_Article $article = null,
		$number = null, $createTime = null) {
		parent::__construct();
		
		if (!is_int($id) || $id < 0)
			throw new InvalidArgumentException(
				'$id must be a positive integer value');
		
		if ($number !== null && (!is_int($number) || $number < 1))
			throw new InvalidArgumentException('$number must be a positive '.
				'integer or null');
		if ($createTime !== null && !is_int($createTime))               
			throw new InvalidArgumentException('$createTime must be an integer or '.
				'null');
				
		$this->_id = $id;
		$this->_article = $article;
		$this->_number = $number;
		$this->_createTime = $createTime;
	}         

	// ===========================================================================   
          
	/**
	 * Creates a revision that exists already in data base using its article and
	 * revision number
	 *
	 * @param Blog_Article $id
	 * @param int $number
	 */                     
	public static function createFromNumber(Blog_Article $article, $number) {
		if ($article == null)
			throw new InvalidArgumentException('$article must not be null');
		if (!is_int($number) || $number < 1)
			throw
				new InvalidArgumentException('$number must be a positive integer');
				
		$result = DataBase::query(
			"SELECT revision.revisionID, ".
				"UNIX_TIMESTAMP(revision.createTime) AS createTime ".
			"FROM ".table('Blog_Revisions')." AS revision ".
			"WHERE revision.articleID = '".$article->id."' ".
				"AND revision.languageID = '".Premanager::$language->id."' ".
				"AND revision.revision = '$number'");
		if ($result->next())
			return new Revision($result->value('revisionID'), $article, $number,
				$result->value('createTime'));
		else
			throw new OutOfBoundsException('$number is not a valid revision number '.
				'in the specified article and the current language');
	} 

	// ===========================================================================
	                 
	/**
	 * Gets the id of this revision
	 *
	 * @return int
	 */
	public function getID() { 
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
			
		return $this->_id;
	}
        
	/**
	 * Gets the article that contains this revision
	 *
	 * @return Blog_Article
	 */
	public function getArticle() {
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
			
		if ($this->_article === null) {
			if ($this->_articleID === null)
				$this->load();
			$this->_article = new Blog_Article($id());		
		}
		return $this->_article;	
	}  
	               
	/**
	 * Gets the revision number of this revision
	 *
	 * @return int
	 */
	public function getNumber() {     
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
			
		if ($this->_number == null)
			$this->load();
		return $this->_number;	
	}
	                           
	/**
	 * Gets the time this revision has been created
	 *
	 * @return int a unix timestamp
	 */
	public function getCreateTime() {      
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
			
		if ($this->_createTime === null)
			$this->load();
		return $this->_createTime;	
	}      
	                         
	/**
	 * Gets the raw text (pml string) of this revision
	 *
	 * @return string
	 */
	public function getText() {	
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
					
		if ($this->_text === null) {
			$result = DataBase::query(
				"SELECT revision.text ".  
				"FROM ".table('Blog_Revisions')." AS revision ".
				"WHERE revision.revisionID = '$this->_id'");
			$this->_text = $result->value('text');
		}

		return $this->_text;	
	}   
	              
	/**
	 * Makes this revision the published revision of the article
	 */
	public function publish() { 
		if ($this->_id === null)
			throw new InvalidOperationException('Revision is deleted');
			
		DataBaseHelper::update('Blog_Articles', 'articleID',
			CREATOR_FIELDS | EDITOR_FIELDS,
			$this->article->id, $this->article->name,
			array(),
			array('publishedRevisionID' => $this->_id)
		);
		$this->article->internalSetPublishedRevision($this);
	}       

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT revision.revision, revision.articleID, ".
				"UNIX_TIMESTAMP(revision.createTime) AS createTime ".
			"FROM ".table('Blog_Revisions')." AS revision ".
			"WHERE revision.revisionID = '$this->_id'");
		if ($result->next()) {
			$this->_number = $result->value('revision');      
			$this->_articleID = $result->value('articleID');
			$this->_createTime = $result->value('createTime');
		} else
			throw new UnexpectedStateException('Invalid revision id found');            				
	}
}

?>