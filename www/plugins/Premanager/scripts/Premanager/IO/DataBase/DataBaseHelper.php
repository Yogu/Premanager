<?php
namespace Premanager\IO\DataBase;

use Premanager\Strings;

use Premanager\ArgumentException;

use Premanager\Execution\Environment;

use Premanager\IO\Request;

use Premanager\Module;

/**
 * Static class with useful data base methods
 */
class DataBaseHelper extends Module {
	const CREATOR_FIELDS = 0x01;
	const EDITOR_FIELDS = 0x02;
	const IS_TREE = 0x04;
	const IGNORE_THIS = 0x08;
	const UNTRANSLATED_NAME = 0x10;    

	// ===========================================================================
	
	/**
	 * Inserts a new object into data base
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags a set (CREATOR_FIELDS: table contains createTime,
	 *   creatorID and creatorIP fields; EDITOR_FIELDS: table contains editTime,
	 *   editorID, editorIP and editTimes fields; IS_TREE: table is a tree table; 
	 *   UNTRANSLATED_NAME: name field is in item table instead of translation
	 *   table)
	 * @param string name: name value (can be omitted at values / translatedValues
	 *   parameter)
	 * @param array $values array with name-value pairs to insert into the item
	 *   table (if name ends with ! the value will not be escaped)
	 * @param array $translatedValues array with name-value pairs to insert into
	 *   the translated table (if name ends with ! the value will not be escaped)
	 * @param int|null $parentID id of parent item, if $flags contains IS_TREE
	 *   (can be omitted at values parameter)
	 * @return int id of created item
	 */
	public static function insert($table, $flags, $name,
		array $values, array $translatedValues, $parentID = null) {
		$me = Environment::getCurrent()->me->id;
		$ip = Request::getIP();
		$lang = Environment::getCurrent()->language->id;   
		
		if (($flags & self::IS_TREE)) {
			if (!is_int($parentID) || $parentID < 0)
				throw new ArgumentException('$parentID must be a nonnegative integer '.
					'value if $flags contains IS_TREE');
		}
		
		// -------------- Item ------------
			
		// Extend value array
		if ($flags & self::CREATOR_FIELDS) {
			$values['createTime!'] = 'NOW()';
			$values['creatorID'] = $me;
			$values['creatorIP'] = $ip;
		}
		
		if ($flags & self::EDITOR_FIELDS) {  
			$values['editTime!'] = 'NOW()';
			$values['editorID'] = $me;
			$values['editorIP'] = $ip;
			$values['editTimes'] = 0;
		}

		if ($flags & self::IS_TREE)
			$values['parentID'] = $parentID;
			
		if ($flags & self::UNTRANSLATED_NAME)
			$values['name'] = $name;
		else
			$translatedValues['name'] = $name;
		   
		// Prepare query
		$nameString = '';
		$valueString = '';
		foreach ($values as $n => $v) {
			if (is_bool($v)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($name, 0, Strings::length($name)-1);
				$nameString .= ", $n";
				$valueString .= ", $v";
			} else {
				$nameString .= ", $n";
				$valueString .= ", '".DataBase::escape($v)."'";
			}
		} 
		$nameString = trim($nameString, ', '); 
		$valueString = trim($valueString, ', ');
	
		// Execute query
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName($table)." ".
			"($nameString) ".
			"VALUES ($valueString)");
		$id = DataBase::getInsertID();
			
		// -------------- Translation ------------
		
		// Extend value array
		$translatedValues['id'] = $id;
		$translatedValues['languageID'] = $lang; 
		
		// Prepare query
		$nameString = '';
		$valueString = '';
		foreach ($translatedValues as $n => $v) {
			if (is_bool($value)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($n, 0, Strings::length($n)-1);
				$nameString .= ", $n";
				$valueString .= ", $v";
			} else {
				$nameString .= ", $n";
				$valueString .= ", '".DataBase::escape($v)."'";
			}
		} 
		$nameString = trim($nameString, ', '); 
		$valueString = trim($valueString, ', ');
		
		// Execute query
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName($table.'Translation')." ".
			"($nameString) ".
			"VALUES ($valueString)");

		// -------------- Name ------------
		
		self::insertName($table, $flags, $id, $name, $parentID);
		
		return $id;
	}
	        

	/**
	 * Deletes an item from data base
	 *
	 * Deletes an item, its translations and names assigned to it, but does _not_
	 * delete children (in a tree) 
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags always 0 (reserved for future use)
	 * @param int $id id of item to delete
	 * @return int id of created item
	 */
	public static function delete($table, $flags, $id) {
		if (!is_int($id) || $id < 0)
			throw new ArgumentException('$id must be a positive integer value', 'id');
		
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName($table)." ".
			"WHERE id = '$id'");

		DataBase::query(
			"DELETE FROM ".DataBase::formTableName($table.'Translation')." ".
			"WHERE id = '$id'");	
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName($table.'Name')." ".
			"WHERE id = '$id'");	
	}   
	
	/**
	 * Updates values of an existing item
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags a set (CREATOR_FIELDS: table contains createTime,
	 *   creatorID and creatorIP fields; EDITOR_FIELDS: table contains editTime,
	 *   editorID, editorIP and editTimes fields; IS_TREE: table is a tree table; 
	 *   UNTRANSLATED_NAME: name field is in item table instead of translation
	 *   table)       
	 * @param int $id: id of item that should be updated
	 * @param string|null name: name value (can be omitted at values /
	 *   translatedValues parameter); if null, name is not updated
	 * @param array $values array with name-value pairs to insert into the item
	 *   table (if name ends with ! the value will not be escaped)
	 * @param array $translatedValues array with name-value pairs to insert into
	 *   the translated table (if name ends with ! the value will not be escaped)
	 * @param int|null $parentID id of parent item, if $flags contains IS_TREE
	 *   (can be omitted at values parameter)
	 * @param callback|null $isNameInuseCallback callback function that specifies
	 *   whether a name is still in use, or null to use the default method
	 */
	public static function update($table, $flags, $id, $name,
		array $values, array $translatedValues, $parentID = null,
		$isNameInUseCallback = null) {
		$me = Environment::getCurrent()->me->id;
		$ip = Request::getIP();
		$lang = Environment::getCurrent()->language->id;
		
		if (!is_int($id) || $id < 0)
			throw new rgumentException('$id must be a positive integer value', 'id');
		
		if (($flags & self::IS_TREE)) {
			if (!is_int($parentID) || $parentID < 0)
				throw new InvalidArgumentException('$flags contains IS_TREE, but '.
					'parentID is not an nonnegative integer');
		}
		
		// -------------- Item ------------
			
		// Extend value array		
		if ($flags & self::EDITOR_FIELDS) {  
			$values['editTime!'] = 'NOW()';
			$values['editorID'] = $me;
			$values['editorIP'] = $ip;
			$values['editTimes!'] = 'editTimes + 1';
		}       
			
		if ($name !== null) {
			if ($flags & self::UNTRANSLATED_NAME)
				$values['name'] = $name;
			else
				$translatedValues['name'] = $name;
		}
		   
		// Prepare query
		$queryString = '';
		foreach ($values as $n => $v) {
			if (is_bool($value)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($n, 0, Strings::length($n)-1);
				$queryString .= ", $n = $value";
			} else {
				$queryString .= ", $n = '".DataBase::escape($v)."'";
			}
		} 
		$queryString = trim($queryString, ', ');

		// Execute query
		if ($queryString) {
			DataBase::query(
				"UPDATE ".DataBase::formTableName($table)." ".
				"SET $queryString ".
				"WHERE id = '$id'");
		}		
			
		// -------------- Translation ------------
		
		// Check if translation already exists
		$result = DataBase::query(
			"SELECT languageID ".
			"FROM ".DataBase::formTableName($table.'Translation')." translation ".
			"WHERE translation.id = '$id' ".
				"AND translation.languageID = '$lang'");
		if ($result->next()) {
			// Prepare query
			$queryString = '';
			foreach ($translatedValues as $n => $v) {
				if (is_bool($v)) $v = $v ? '1' : '0';
				if ($n[String::length($n)-1] == '!') {
					$n = Strings::substring($n, 0, String::length($n)-1);
					$queryString .= ", $n = $v";
				} else {
					$queryString .= ", $n = '".DataBase::escape($v)."'";
				}
			} 
			$queryString = trim($queryString, ', ');
			
			// Execute query   
			if ($queryString) {
				DataBase::query(
					"UPDATE ".DataBase::formTableName($table.'Translation')." ".
					"SET $queryString ".
					"WHERE id = '$id' ".
						"AND languageID = '$lang'");
			}
		} else {           
			// Extend value array
			$translatedValues['id'] = $id;
			$translatedValues['languageID'] = $lang;
			            
			// Prepare query
			$nameString = '';
			$valueString = ''; 
			foreach ($translatedValues as $n => $v) {
				if (is_bool($v)) $v = $v ? '1' : '0';
				if ($n[Strings::length($n)-1] == '!') {
					$n = Strings::substring($n, 0, Strings::length($n)-1);
					$nameString .= ", $n";
					$valueString .= ", $v";
				} else {
					$nameString .= ", $n";
					$valueString .= ", '".DataBase::escape($v)."'";
				}
			} 
			$nameString = trim($nameString, ', '); 
			$valueString = trim($valueString, ', ');
			
			// Execute query
			if ($nameString) {
				DataBase::query(
					"INSERT INTO ".DataBase::formTableName($table.'Translation')." ".
					"($nameString) ".
					"VALUES ($valueString)");
			}
		}
	
		if ($name !== null) {
			// -------------- Name ------------
			$this->insertName($table, $flags, $id, $name, $parentID);
			
			// Check names assigned to this item, check if they are still in use and
			// update, if neccessary, their language
			$result = DataBase::query(
				"SELECT name.nameID, name.name, name.languageID ".
				"FROM ".DataBase::formTableName($table.'Name')." AS name ".
				"WHERE name.id = '$id' ".
					"AND name.inUse = '1'");
			while ($result->next()) {
				$name = DataBase::escape($result->get('name'));
				$nameID = $result->get('nameID');
				
				$isInUse = false;
				$languageID = null;
	
				if (is_callable($isNameInUseCallback))
					$isInUse = $isNameInUseCallback($name, &$languageID);
				else if ($flags & self::UNTRANSLATED_NAME) {
					$result2 = DataBase::query(
						"SELECT item.id ".
						"FROM ".DataBase::formTableName($table)." AS item ".   
						"WHERE item.id = '$id' ".
							"AND LOWER(item.name) = '$name'");
					$isInUse = $result2->next();
					$languageID = $result2->value('languageID');				
				} else {
					$result2 = DataBase::query(
						"SELECT translation.languageID ".
						"FROM ".DataBase::formTableName($table.'Translation').
							" AS translation ".
						"WHERE translation.id = '$id' ".
							"AND LOWER(translation.name) = '$name'");  
					$isInUse = $result2->next();
					$languageID = $result2->value('languageID');
				}  
						
				// If there is no translation with this name, set inUse
				if (!$isInUse) {
					DataBase::query(
						"UPDATE ".DataBase::formTableName($table.'Name')." ".
						"SET inUse = '0' ".
						"WHERE nameID = '$nameID'");
				
				// If language has changed, update this
				} else if (!($flags & self::UNTRANSLATED_NAME) &&
					$result->get('languageID') != $languageID) {
					DataBase::query(
						"UPDATE ".DataBase::formTableName($table.'Name')." ".
						"SET languageID = '$languageID' ".
						"WHERE nameID = '$nameID'");  							
				}			
			}
		}  
	}  
	
	/**
	 * Checks if a name is available for a type of items
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags a set (IS_TREE: table is a tree table; IGNORE_THIS: names
	 *   that are assigned to $id should be ignored)   
	 * @param string $name: name to be checked       
	 * @param int|null $id: if $flags contains IGNORE_THIS, specifies the item
	 *   whose names should be ignored
	 * @param int|null $parentID id of parent item, if $flags contains IS_TREE
	 * @return bool true, if this name is available, otherwise, false
	 */
	public static function isNameAvailable($table, $flags, $name,
		$id = null, $parentID = null) {
		
		if (($flags & self::IGNORE_THIS)) {
			if (!is_int($id) || $id < 0)
				throw new ArgumentException(
					'$flags contains IGNORE_THIS, but $id is not a nonnegative integer');
		}
		
		if (($flags & self::IS_TREE)) {
			if (!is_int($parentID) || $parentID < 0)
				throw new ArgumentException('$flags contains IS_TREE, but $parentID '.
					'is not a nonnegative integer');
		}
		
		$result = DataBase::query(
			"SELECT name.nameID ".
			"FROM ".DataBase::formTableName($table.'Name')." name ".
			"INNER JOIN ".DataBase::formTableName($table)." item ".
				"ON name.id = item.id ".
			"WHERE name.name = '".DataBase::escape(Strings::unitize($name))."' ".
				($flags & self::IS_TREE ? "AND item.parentID = '$parentID' " : '').
				"AND inUse ".
				($flags & self::IGNORE_THIS ? "AND item.id != '$id'" : ''));
		return !$result->next();
	}
	         
	/**
	 * Re-inserts all names of this item into name table
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags a set (IS_TREE: table is a tree table; UNTRANSLATED_NAME:
	 *   name field is in item table instead of translation table)
	 * @param int $id id of item whose items should be updated
	 */
	public static function rebuildNameTable($table, $flags, $id) {    
		if (!is_int($id) || $id < 0)
			throw new ArgumentException('$id must be a positive integer value', 'id');
				
		$result = DataBase::query(
			"SELECT ".
				($flags & self::IS_TREE ? "item.parentID, " : '').
				($flags & self::UNTRANSLATED_NAME ? "item.name, " : "translation.name").
			"FROM ".DataBase::formTableName($table)." AS item ",
			/* translating */
			"WHERE item.id = '$id'");
		while ($result->next()) {
			self::insertName($table, $flags, $id, $result->get('name'),
				$flags & self::IS_TREE ? $result-->value('parentID') : null);
		}
	}      

	// ===========================================================================
	
	/**
	 * Appends a name to an item
	 *
	 * If this name does already exist, it is moved to the specified item.
	 *
	 * @param string $table name of item table (_un_encoded)
	 * @param int $flags a set (IS_TREE: table is a tree table) 
	 * @param int $id id of item to which the name has to be assigned
	 * @param string $name name to assign
	 * @param int|null $parentID id of parent item, if $flags contains IS_TREE
	 */
	private static function insertName($table, $flags, $id, $name,
		$parentID = null) {  
		$lang = Environment::getCurrent()->language->id;
		$name = DataBase::escape(unitize($name));
		
		if (!is_int($id) || $id < 0)
			throw new ArgumentException('$id must be a positive integer value', 'id');
		
		if ($flags & self::IS_TREE) {
			if (!is_int($parentID) || $parentID < 0)
				throw new ArgumentException('$parentID must be a positive integer '.
					'value if $flags contains IS_TREE');
					
			$result = DataBase::query(
				"SELECT nameID ".
				"FROM ".DataBase::formTableName($table.'Name')." AS name ".
				"INNER JOIN ".DataBase::formTableName($table)." AS item ".
					"ON name.id = item.id ".
				"WHERE item.parentID = '$parentID' ".
					"AND LOWER(name.name) = '$name'");
		} else {
			$result = DataBase::query(
				"SELECT nameID ".
				"FROM ".DataBase::formTableName($table.'Name')." AS name ".
				"WHERE LOWER(name.name) = '$name'");
		}
			
		if ($result->next()) {
			DataBase::query(
				"UPDATE ".DataBase::formTableName($table.'Name')." ".
				"SET inUse = '1', ".
					"languageID = '$lang', ".
					"id = '$id' ".
				"WHERE nameID = '".$result->get('nameID')."'");
		} else {
			DataBase::query(
				"INSERT INTO ".DataBase::formTableName($table.'Name')." ".
				"(id, name, languageID, inUse) ".
				"VALUES ('$id', '$name', '$lang', '1')");           	
		}
	}
	
	//??? Has to be implemented seperately into each model class
	/*// Returns $request, if it is available, otherwise returns $request plus a
	//   suffix          
	// string request: This name will probably be returned, or something similar      
	// set flags: set of
	//   IGNORE_THIS: names of this.id has to be ignored
	// Throws Exception, if this object does not have an implementation of
	//   isNameAvailable method  
	public static function getAvailableName($request, $flags) {
		if (!method_exists($this, 'isNameAvailable'))
			throw new Exception('getAvailableName was called on an object that does '.
				'not implement isNameAvailable() method');

		if ($this->isNameAvailable($request))
			return $request;
		else {
			$index = 2;
			while (!$this->isNameAvailable($request.$index))
				$index++;
			return $request.$index;		
		}	
	}*/
}

?>
