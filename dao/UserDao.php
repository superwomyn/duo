<?php

class UserDao extends Dao {

	protected $cacheKey = "User::";

    function __construct($application){
	   	parent::__construct($application);
	}
	
	/* ---- READ OPS ---- */
	public function findByUserId($userId){
		$user = $this->getFromCache($userId);
		if ($user){
			return $user;
		} else {
			$queryStr = "select id, is_deleted, email, first_name, last_name, date_created, date_updated ";
			$queryStr .= "from user where id = %s";
			$query = sprintf($queryStr,
			mysql_real_escape_string($userId));
			$this->logQuery("", $query);
			$result = mysql_query($query);
			if (!$result || mysql_num_rows($result) == 0)  {
				return null;
			}
			$user = new User();
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$user->setId($row["id"]);
				$user->setIsDeleted($row["is_deleted"]);
				$user->setEmail($row["email"]);
				$user->setFirstName($row["first_name"]);
				$user->setLastName($row["last_name"]);
				$user->setDateCreated($row["date_created"]);
				$user->setDateUpdated($row["date_updated"]);
			}
			mysql_free_result($result);
			$this->addToCache($user->getId(), $user);
			return $user;
		}
	}

	public function findUserByEmailAndPassword($email, $password){
		$queryStr = "select id ";
		$queryStr .= "from user where email = '%s' ";
		$queryStr .= "and password = '%s' ";
		if (!empty($password)){
		    $md5Password = md5($password);
		} else {
		    $md5Password = "";
		}
		$query = sprintf($queryStr,
		mysql_real_escape_string($email),
		mysql_real_escape_string($md5Password));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result || mysql_num_rows($result) == 0)  {
			return null;
		}
		$user = null;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user = $this->findByUserId($row["id"]);
		}
		mysql_free_result($result);
		return $user;
	}
	
    public function findUserByEmail($email){
		$queryStr = "select id ";
		$queryStr .= "from user where email = '%s' ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($email));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result || mysql_num_rows($result) == 0)  {
			return null;
		}
		$user = null;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user = $this->findByUserId($row["id"]);
		}
		mysql_free_result($result);
		return $user;
	}

	/* ---- WRITE OPS ---- */
	public function createUser(User $user){
	    $now = date("Y-m-d H:i:s");
		$existingUser = $this->findUserByEmail($user->getEmail());
		if (!is_null($existingUser) && !$existingUser->isDeleted()) {
		    throw new DuplicateUserException();
		} else {    
		    $queryStr = "insert into user (email, first_name, last_name, date_created, date_updated) ";
    		$queryStr .= "values ('%s', '%s', '%s', '%s', '%s') ";
    		$query = sprintf($queryStr,
    		mysql_real_escape_string($user->getEmail()),
    		mysql_real_escape_string($user->getFirstName()),
    		mysql_real_escape_string($user->getLastName()),
    		mysql_real_escape_string($now),
    		mysql_real_escape_string($now));
    		$this->logQuery("", $query);
    		$result = mysql_query($query);
    		if (!$result)  {
    			return null;
    		}
    		$userId = mysql_insert_id();
    		$userFromDb = $this->findByUserId($userId);
    		return $userFromDb;  
		} 
	}

	public function updateUser(User $user){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update user set ";
		$queryStr .= "email = '%s', ";
		$queryStr .= "first_name = '%s', ";
		$queryStr .= "last_name = '%s', ";
		$queryStr .= "date_updated = '%s' ";
		$queryStr .= "where id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($user->getEmail()),
		mysql_real_escape_string($user->getFirstName()),
		mysql_real_escape_string($user->getLastName()),
		mysql_real_escape_string($now),
		mysql_real_escape_string($user->getId())
		);
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return false;
		}
		$this->addToCache($user->getId(), $user);
		return true;
	}
	
	public function updatePassword(User $user, $password){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update user set ";
		$queryStr .= "password = '%s', ";
		$queryStr .= "date_updated = '%s' ";
		$queryStr .= "where id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string(md5($password)),
		mysql_real_escape_string($now),
		mysql_real_escape_string($user->getId())
		);
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return false;
		}
		// since date_updated changed, we need to update user in the cache
		$userFromCache = $this->getFromCache($user->getId());
		if(!is_null($userFromCache)){
		    $userFromCache->setDateUpdated($now);
		    $this->addToCache($user->getId(), $userFromCache);
            return $userFromCache;    
		} else {
		    return $user;
		}

	}
	
	public function deleteUser(User $user){
		$now = date("Y-m-d H:i:s");
		$queryStr = "update user set ";
		$queryStr .= "is_deleted = 1, ";
		$queryStr .= "date_updated = '%s' ";
		$queryStr .= "where id = %s ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($now),
		mysql_real_escape_string($user->getId())
		);
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return false;
		}
		$user->setIsDeleted(true);
		$this->addToCache($user->getId(), $user);
		return true;
	}
	
	public function permanentlyDeleteUser(User $user){
	    $now = date("Y-m-d H:i:s");
		$queryStr = "delete from user where id = %s ";
    	$query = sprintf($queryStr, mysql_real_escape_string($user->getId()));
    	$this->logQuery("", $query);
    	$result = mysql_query($query);
    	if (!$result)  {
    		return false;
    	} else {
    		$this->deleteFromCache($user->getId());
    		return true;
    	}
	}

}
?>