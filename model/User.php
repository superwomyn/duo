<?php

class User extends Domain implements IUser {

	private $isDeleted;
	private $email;
	private $firstName;
	private $lastName;
	private $session; // transient
	
	
	public function isDeleted(){
		return $this->isDeleted;
	}
	public function setIsDeleted($isDeleted){
		$this->isDeleted = $isDeleted;
	}
	
	public function getEmail(){
		return $this->email;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getFirstName(){
		return $this->firstName;
	}
	public function setFirstName($firstName){
		$this->firstName = $firstName;
	}
	
	public function getLastName(){
		return $this->lastName;
	}
	public function setLastName($lastName){
		$this->lastName = $lastName;
	}
	
	public function getSession(){
		return $this->session;
	}
	public function setSession($session){
		$this->session = $session;
	}
	
}

?>