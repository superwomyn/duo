<?php

class ResetPasswordDao extends Dao {

    function __construct($application){
	    parent::__construct($application);
	}
	
	/* ---- READ OPS ---- */
	public function findByCode($code){
			$queryStr = "select id, user_id, code, expiry, redeemed, date_created, date_updated ";
			$queryStr .= "from reset_password where ";
			$queryStr .= "code = '%s' ";
			$query = sprintf($queryStr,
			mysql_real_escape_string(md5($code)));
			$this->logQuery("", $query);
			$result = mysql_query($query);
			if (!$result || mysql_num_rows($result) == 0)  {
				return null;
			}
			$resetPassword = new ResetPassword();
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$resetPassword->setId($row["id"]);
				$resetPassword->setUserId($row["user_id"]);
				$resetPassword->setCode($row["code"]);
				$resetPassword->setExpiry($row["expiry"]);
				$resetPassword->setRedeemed($row["redeemed"]);
				$resetPassword->setDateCreated($row["date_created"]);
				$resetPassword->setDateUpdated($row["date_updated"]);
			}
			mysql_free_result($result);
			return $resetPassword;
	}

	

	/* ---- WRITE OPS ---- */
	public function createCode($userId, $code){
	    $now = date("Y-m-d H:i:s");
		$queryStr = "insert into reset_password (user_id, code, expiry, redeemed, date_created, date_updated) ";
   		$queryStr .= "values (%s, '%s', from_unixtime(unix_timestamp() + 432000), %s, '%s', '%s') ";
   		$query = sprintf($queryStr,
   		mysql_real_escape_string($userId),
   		mysql_real_escape_string(md5($code)),
   		mysql_real_escape_string($this->escapeBoolean(false)),
   		mysql_real_escape_string($now),
   		mysql_real_escape_string($now));
   		$this->logQuery("", $query);
   		$result = mysql_query($query);
   		if (!$result)  {
   			return null;
   		}
   		return $code;  
	}
	
	public function updateCode($user_id, $code, $redeemed){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update reset_password set ";
   		$queryStr .= "redeemed = %s, ";
   		$queryStr .= "date_updated = '%s' ";
   		$queryStr .= "where code = '%s' ";
   		$queryStr .= "and user_id = %s";
   		$query = sprintf($queryStr,
   		mysql_real_escape_string($this->escapeBoolean($redeemed)),
   		mysql_real_escape_string($now),
   		mysql_real_escape_string(md5($code)),
   		mysql_real_escape_string($user_id));
   		$this->logQuery("", $query);
   		$result = mysql_query($query);
   		if (!$result)  {
   			return null;
   		}
   		return $code;  
	}

}
?>