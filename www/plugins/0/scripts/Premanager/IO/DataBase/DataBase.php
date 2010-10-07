<?php
namespace Premanager\IO\DataBase;

use Premanager\IO\DataBase\DataBaseConnection;
use Premanager\Module;
use Premanager\IO\Config;

class DataBase extends Module {
	private static $_connection;
	
	/**
	 * Gets the default data base connection
	 * 
	 * @return Premanager\IO\DataBase\DataBaseConnection
	 */
	public static function getConnection() {
		if (!self::$_connection) {
			self::$_connection = new DataBaseConnection(Config::getDataBaseHost(),
				Config::getDataBaseUser(), Config::getDataBasePassword(),
				Config::getDataBaseName(), Config::getDataBasePrefix());
		}
		return self::$_connection;
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
	public static function query($query, $rightPart = null) {
		return self::getConnection()->query($query, $rightPart);
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
	 * @return Premanager\IO\DataBase\DataBaseResult the result if it is a WHERE
	 *   query, otherwise null
	 */
	public static function queryAndLog($query, $rightPart = null) {
		return self::getConnection()->queryAndLog($query, $rightPart, 1);
	}
	
	/**
	 * Gets the id value of the last inserted row
	 * 
	 * @return int
	 */
	public static function getInsertID() {
		return self::getConnection()->getInsertID();
	}
	
	/**
	 * Gets count of rows that were affected at the last query
	 * 
	 * @return int
	 */
	public static function getAffectedRowCount() {
		return self::getConnection()->getAffectedRowCount();
	}
	
	/**
	 * Escapes a string using the default connection
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function escape($str) {
		return self::getConnection()->escape($str);
	}
	
	/**
	 * Adds the table prefix and does further neccessary conversion using the
	 * default connection
	 * 
	 * @param string $plugin the name of the plugin owning the table
	 * @param string $table the raw table name
	 * @return string the formatted table identifier
	 */
	public static function formTableName($plugin, $table) {
		return self::getConnection()->formTableName($plugin, $table);
	}
}

?>