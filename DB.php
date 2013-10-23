<?php

class DB {

	public static function getConnection($applicationContext){
	
		$dbHostname = $applicationContext->getProperty("db_hostname");
		$dbUsername = $applicationContext->getProperty("db_username");
		$dbPassword = $applicationContext->getProperty("db_password");
		$dbName = $applicationContext->getProperty("db_name");
	
		$conn = mysql_connect($dbHostname, $dbUsername, $dbPassword);
		$db = mysql_select_db($dbName, $conn);

		if (!$db) {
			die('Cannot connect to database.');
		}
		return $conn;
	}

}

?>