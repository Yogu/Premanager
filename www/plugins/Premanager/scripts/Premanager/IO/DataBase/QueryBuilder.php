<?php
namespace Premanager\IO\DataBase;

/**
 * Provides static methods to build a query string
 */
class QueryBuilder {
	/**
	 * Builds an INSERT query
	 * 
	 * @param string $table the table name
	 * @param array $values an associative array with names => values
	 * @return string the SQL query
	 */
	public static function buildInsertQuery($table, array $values)
	{
		$nameString = '';
		$valueString = '';
		foreach ($values as $n => $v) {
			if (is_bool($v)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($n, 0, Strings::length($n)-1);
				$nameString .= ", `$n`";
				$valueString .= ", $v";
			} else {
				$nameString .= ", `$n`";
				$valueString .= ", '".DataBase::escape($v)."'";
			}
		} 
		$nameString = trim($nameString, ', '); 
		$valueString = trim($valueString, ', ');
		
		return "INSERT INTO `$table` ($nameString) VALUES ($valueString)";
	}
	
	/**
	 * Builds an UPDATE query
	 * 
	 * UPDATE `$table`
	 * SET `$name1` = '$value1', `$name2` = '$value2' ...
	 * [WHERE $condition]
	 * 
	 * @param string $table the table to update
	 * @param array $values an associative array with names => values
	 * @param string $condition an SQL expression. Optional.
	 * @return the SQL query (empty if $values is empty)
	 */
	public static function buildUpdateQuery($table, array $values,
		$condition = '')
	{
		if (!count($values))
			return '';
		
		$queryString = '';
		foreach ($values as $n => $v) {
			if (is_bool($v)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($n, 0, Strings::length($n)-1);
				$queryString .= ", `$n` = $v";
			} else {
				$queryString .= ", `$n` = '".DataBase::escape($v)."'";
			}
		} 
		$queryString = trim($queryString, ', ');
		
		return "UPDATE `$table` SET $queryString ".
			($condition ? "WHERE $condition" : '');
	}
	
	/**
	 * Builds a REPLACE query
	 * 
	 * REPLACE INTO `$table`
	 * SET `$name1` = '$value1', `$name2` = '$value2' ...
	 * 
	 * @param string $table the table to update
	 * @param array $values an associative array with names => values
	 * @return the SQL query (empty if $values is empty)
	 */
	public static function buildReplaceQuery($table, array $values) {
		if (!count($values))
			return '';
		
		$queryString = '';
		foreach ($values as $n => $v) {
			if (is_bool($v)) $v = $v ? '1' : '0';
			if ($n[Strings::length($n)-1] == '!') {
				$n = Strings::substring($n, 0, Strings::length($n)-1);
				$queryString .= ", `$n` = $v";
			} else {
				$queryString .= ", `$n` = '".DataBase::escape($v)."'";
			}
		} 
		$queryString = trim($queryString, ', ');
		
		return "REPLACE INTO `$table` SET $queryString";
	}
}

?>
