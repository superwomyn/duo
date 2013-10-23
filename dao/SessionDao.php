<?php

class SessionDao extends Dao {

    function __construct($application){
	    parent::__construct($application);
	}
	
	/* ---- READ OPS ---- */
	public function findBySessionId($sessionId){
		$queryStr = "select id, valid, session_key, user_id, expiry, ip_address, date_created, date_updated ";
		$queryStr .= "from session where id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($sessionId));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result || mysql_num_rows($result) == 0)  {
			return null;
		}
		$session = null;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$session = new Session();
			$session->setId($row["id"]);
			$session->setValid($row["valid"]);
			$session->setSessionKey($row["session_key"]);
			$session->setUserId($row["user_id"]);
			$session->setExpiry($row["expiry"]);
			$session->setIpAddress($row["ip_address"]);
			$session->setDateCreated($row["date_created"]);
			$session->setDateUpdated($row["date_updated"]);
		}
		mysql_free_result($result);
		return $session;
	}
	 
	public function findBySessionKey($sessionKey){
		$queryStr = "select id, valid, session_key, user_id, expiry, ip_address, date_created, date_updated ";
		$queryStr .= "from session where session_key = '%s' ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($sessionKey));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result || mysql_num_rows($result) == 0)  {
			return null;
		}
		$session = null;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$session = new Session();
			$session->setId($row["id"]);
			$session->setValid($row["valid"]);
			$session->setSessionKey($row["session_key"]);
			$session->setUserId($row["user_id"]);
			$session->setExpiry($row["expiry"]);
			$session->setIpAddress($row["ip_address"]);
			$session->setDateCreated($row["date_created"]);
			$session->setDateUpdated($row["date_updated"]);
		}
		mysql_free_result($result);
		return $session;
	}
	
	/* ---- WRITE OPS ---- */
	public function createSession($user, $ipAddress){
		$now = date("Y-m-d H:i:s");
		$sessionKey = md5($user->getId() + rand());
		$queryStr = "insert into session ";
		$queryStr .= "(valid, session_key, user_id, expiry, ip_address, date_created, date_updated) ";
		$queryStr .= "values (1, '%s', %s, from_unixtime(unix_timestamp() + 1800), '%s', '%s', '%s' ) ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($sessionKey),
		mysql_real_escape_string($user->getId()),
		mysql_real_escape_string($ipAddress),
		mysql_real_escape_string($now),
		mysql_real_escape_string($now)
		);
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return null;
		}
		$sessionId = mysql_insert_id();
		$session = $this->findBySessionId($sessionId);
		return $session;
	}
	 
	public function updateSessionExpiry($sessionId){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update session ";
		$queryStr .= "set expiry = from_unixtime(unix_timestamp() + 1800), ";
		$queryStr .= "date_updated = '%s' ";
		$queryStr .= "where id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($now),
		mysql_real_escape_string($sessionId));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return null;
		}
		$session = $this->findBySessionId($sessionId);
		return $session;
	}
	 
	public function invalidateAllSessionsForUserId($userId){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update session ";
		$queryStr .= "set valid = 0, ";
		$queryStr .= "date_updated = '%s' ";
		$queryStr .= "where user_id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($now),
		mysql_real_escape_string($userId));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return null;
		}
	}
	 
}
?>