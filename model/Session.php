<?php

class Session extends Domain {

	private $valid;
	private $sessionKey;
	private $userId;
	private $expiry;
	private $ipAddress;
	
	public function isValid(){
		return $this->valid;
	}
	public function setValid($valid){
		$this->valid = $valid;
	}
	
	public function getSessionKey(){
		return $this->sessionKey;
	}
	public function setSessionKey($sessionKey){
		$this->sessionKey = $sessionKey;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function getExpiry(){
		return $this->expiry;
	}
	public function setExpiry($expiry){
		$this->expiry = $expiry;
	}
	
	public function getIpAddress(){
		return $this->ipAddress;
	}
	public function setIpAddress($ipAddress){
		$this->ipAddress = $ipAddress;
	}

}

?>