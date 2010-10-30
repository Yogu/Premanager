<?php 
namespace Premanager\IO\DataBase;

use Premanager\Models\Plugin;

use Premanager\Module;
use Premanager\Strings;
use Premanager\Debug\Debug;
use Premanager\Execution\Environment;
use Premanager\Models\Language;

class DataBaseConnection extends Module {
	private $_link;
	private $_host;
	private $_user;
	private $_dataBase;
	private $_prefix;
	private $_queryCount;
	
	/**
	 * Creates a new data base connection.
	 * 
	 * If errors occur during connecting, throws
	 * Premanager\IO\DataBase\DataBaseException.
	 * 
	 * @param string $host host name of the data base server
	 * @param string $user user name
	 * @param string $password user's password
	 * @param string $dataBase data base name
	 * @param string $prefix a prefix for tables
	 */
	public function __construct($host, $user, $password, $dataBase, $prefix) {
		parent::__construct();
		
		$this->_host = $host;
		$this->_user = $user;
		$this->_dataBase = $dataBase;
		$this->_prefix = $prefix;
		
		if (!($this->_link = @\mysql_connect($host, $user, $password)))
			throw new DataBaseException('Could not connect to database: '.
				\mysql_error());
                      
		if (!@\mysql_select_db($dataBase, $this->_link))     
			throw new DataBaseException('Could not select database: '.
				$this->getError());
			
		// Set connection character set to UTF-8
		if (!@\mysql_query("SET NAMES 'utf8'"))
			throw new DataBaseException('Could not set data base character set: '.
				$this->getgetError());
		
		// Enable strict mode
		if (!@\mysql_query("SET sql_mode = 'STRICT_ALL_TABLES'"))      
			throw new DataBaseException('Could not enable strict mode in data base: '.
				$this->getError());
			
		if (!@mysql_query("SET time_zone = '+0:00';"))
			throw new DataBaseException("Could not select data base UTC+0 ".
				"timezone: ".$this->getError());
			
		$this->_queryCount = 3;
	}
	
	/**
	 * Executes a query
	 * 
	 * If $rightPart is specified, the translation will be available. 
	 * The final query then will look like this:
	 * $query
	 * INNER JOIN translation
	 * $rightPart
	 * 
	 * @param string $query the query to execute
	 * @param string $rightPart the query part after the translating part
	 * @return Premanager\IO\DataBase\DataBaseResult the result if it is a WHERE
	 *   query, otherwise null
	 */
	public function query($query, $rightPart = null) {
		return $this->internalQuery($query, $rightPart, $rightPart !== null, false,
			0);
	}
	
	/**
	 * Executes a query and writes it into the debug log
	 * 
	 * If $rightPart is specified, the translation will be available. 
	 * The final query then will look like this:
	 * $query
	 * INNER JOIN translation
	 * $rightPart
	 * 
	 * @param string $query the query to execute
	 * @param string $rightPart the query part after the translating part
	 * @param int $indirectCallDepth the count of methods in call stack to be
	 *   excluded from stored call stack
	 * @return Premanager\IO\DataBase\DataBaseResult the result if it is a WHERE
	 *   query, otherwise null
	 */
	public function queryAndLog($query, $rightPart = null,
		$indirectCallDepth = 0) {
		return $this->internalQuery($query, $rightPart, $rightPart !== null, true,
			$indirectCallDepth+1);
	}
	
	/**
	 * Gets the id value of the last inserted row
	 * 
	 * @return int
	 */
	public function getInsertID() {
		return mysql_insert_id($this->_link);
	}
	
	/**
	 * Gets count of rows that were affected at the last query
	 * 
	 * @return int
	 */
	public static function getAffectedRowCount() {
		return \mysql_affected_rows($this->_link);
	}
	
	private function getError() {
		return \mysql_error($this->_link);
	}
	
	private function internalQuery($query, $rest, $doTranslate, $doLog,
		$indirectCallDepth) {
		$this->_queryCount++;
		
		$query = trim($query);
		$rest = trim($rest);
		    
		// Translating query?
		if ($doTranslate) {    
			if (\preg_match( 
				'/^SELECT\s+(?P<fields>.*)\s+FROM\s+(?P<table>\S+)\s+AS\s+'.
				'(?P<shortTable>\S+)/i', $query, &$matches)) {
				$fields = $matches['fields'];
				$table = $matches['table'];
				$shortTable = $matches['shortTable'];  
				$l1 = Environment::getCurrent()->getlanguage()->getid();
				$l2 = Language::getInternationalLanguage()->getid();
				$tt = Strings::toLower($table."Translation");

				$query .= 
					" ".
					"LEFT JOIN $tt AS translation ".
					  "ON $shortTable.id = translation.id ".
					"WHERE ". 
					  "(CASE translation.languageID WHEN $l1 THEN 2 WHEN $l2 THEN 1 ".
							"ELSE 0 END) ".
						"= (".
							"SELECT MAX(CASE translationTemp.languageID WHEN $l1 THEN 2 ".
								"WHEN $l2 THEN 1 ELSE 0 END) ".
							"FROM $table AS translationTempItem ".
							"LEFT JOIN $tt AS translationTemp ".
								"ON translationTempItem.id = translationTemp.id ".
							"WHERE $shortTable.id = translationTempItem.id ".
						") ";
				
				if (Strings::toUpper(Strings::substring($rest, 0, 5)) == 'WHERE')
					$rest = "AND ".Strings::substring($rest, 5);
				$query .= $rest;
			}
		}

		if ($doLog)
			Debug::log($query, $indirectCallDepth+1);           
		
		$mysqlResult = @\mysql_query($query, $this->_link);
		if ($mysqlResult === false)
			throw new SQLException($query, $this->getError());

		// If query was a select query, create result
		if (Strings::toUpper(Strings::substring($query, 0, 6)) == 'SELECT') {
			$result = new DataBaseResult($mysqlResult);
			return $result;
		}   
	} 
	
	/**
	 * Escapes a string
	 * 
	 * @param string $str
	 * @return string
	 */
	public function escape($str) {
		return \mysql_real_escape_string($str, $this->_link);
	}
	
	/**
	 * Adds the table prefix and does further neccessary conversion
	 * 
	 * @param string $plugin the name of the plugin owning the table
	 * @param string $table the raw table name
	 * @return string the formatted table identifier
	 */
	public function formTableName($plugin, $table) {
		$pluginID = Plugin::getIDFromName($plugin);
		return Strings::toLower($this->_prefix.$pluginID.'_'.$table);
	}
}

?>