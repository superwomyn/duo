<?php

class ResetPassword extends Domain {

	private $userId;
	private $code;
	private $expiry;
	private $redeemed;
	
	public function getUserId(){
		return $this->userId;
	}
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	public function getCode(){
		return $this->code;
	}
	public function setCode($code){
		$this->code = $code;
	}
	
	public function getExpiry(){
		return $this->expiry;
	}
	public function setExpiry($expiry){
		$this->expiry = $expiry;
	}
	
	public function wasRedeemed(){
		return $this->redeemed;
	}
	public function setRedeemed($redeemed){
		$this->redeemed = $redeemed;
	}
}

?>