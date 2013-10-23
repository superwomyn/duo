<?php

class DisplayableUser implements IUser {

	private $user;
	
	function __construct(User $user) {
        $this->user = $user;
	}
	
	public function getEmail(){
		return htmlspecialchars($this->user->getEmail());
	}
	
	public function getFirstName(){
		return htmlspecialchars($this->user->getFirstName());
	}
	
	public function getLastName(){
		return htmlspecialchars($this->user->getLastName());
	}
	
	public function getSession(){
		return $this->user->getSession();
	}
	
}

?>