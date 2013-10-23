<?php

abstract class BaseUserService extends BaseService {

	protected $serviceName = "BaseUserService";
	
	function __construct($serviceManager) {
		parent::__construct($serviceManager);
	}

	function __destruct(){
		parent::__destruct();
	}
	
	protected function getServiceName(){
		return $this->serviceName;
	}
	
	public function findUserById($id){
		$userDao = new UserDao($this->application);
		return $userDao->findByUserId($id);
	}
	
	public function findUserByEmail($email){
		$userDao = new UserDao($this->application);
		return $userDao->findUserByEmail($email);
	}

	public function createUserWithPassword($email, $password, $firstName, $lastName){
		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || 
			empty($password) || strlen($password) < CoreConstants::PASSWORD_MIN_LENGTH || 
			empty($firstName) || 
			empty($lastName)){
				throw new InvalidUserDataException();
		}
		$userFromDb = $this->createUser($email, $firstName, $lastName);
		$properties = array("password"=>$password, "confirm_password"=>$password);
		return $this->updatePassword($userFromDb->getId(), $properties);
	}
	
	public function createUser($email, $firstName, $lastName){
		$user = new User();
		$user->setEmail($email);
		$user->setFirstName($firstName);
		$user->setLastName($lastName);
		
		$userDao = new UserDao($this->application);
		$userFromDb = $userDao->createUser($user);
		return $userFromDb;
	}
	
	public function permanentlyDeleteUser($user){
		$userDao = new UserDao($this->application);
		return $userDao->permanentlyDeleteUser($user);
	}
	
	public function deleteUser($user){
		$userDao = new UserDao($this->application);
		return $userDao->deleteUser($user);
	}

	// password is only changeable in a "change password" operation
	public function updateUser($userId, array $properties){
		$userDao = new UserDao($this->application);
		$user = $userDao->findByUserId($userId);
		if (!empty($properties["email"])){
			if (!filter_var($properties["email"], FILTER_VALIDATE_EMAIL)){
				throw new InvalidUserDataException();
			}
			$existingUser = $this->findUserByEmail($properties["email"]);
			if (!is_null($existingUser) && $existingUser->getId() != $userId) {
			    throw new DuplicateUserException();
			} else {    
				// TODO add validation for properly formatted email address
				$user->setEmail($properties["email"]);
			}
		}
		if (!empty($properties["first_name"])){
			$user->setFirstName($properties["first_name"]);
		}
		if (!empty($properties["last_name"])){
			$user->setLastName($properties["last_name"]);
		}
		if ($userDao->updateUser($user)){
			return $user;
		} else {
			return null;
		}

	}

	public function updatePassword($userId, array $properties){
		$userDao = new UserDao($this->application);
		$user = $userDao->findByUserId($userId);
		if (!empty($properties["password"]) && 
			strlen($properties["password"]) >= CoreConstants::PASSWORD_MIN_LENGTH && 
			!empty($properties["confirm_password"]) && 
			strlen($properties["confirm_password"]) >= CoreConstants::PASSWORD_MIN_LENGTH){ 
			if ($properties["password"] == $properties["confirm_password"]){
                return $userDao->updatePassword($user, $properties["password"]);
			} else {
				$this->getLogger()->error("Unable to update password for userId " . $userId);
			}
		}
		return null;
	}
	
	public function generateResetPasswordCode(User $user){
		$resetPasswordDao = new ResetPasswordDao($this->application);
		return $resetPasswordDao->createCode($user->getId(), md5($user->getId()*rand()));
	}
	
	public function findUserByResetPasswordCode($code){
		$resetPasswordDao = new ResetPasswordDao($this->application);
		$resetPassword = $resetPasswordDao->findByCode($code);
		if (is_null($resetPassword)){
			throw new InvalidResetPasswordException();
		}
		if ($this->isResetPasswordCodeValid($resetPassword)){
			throw new ExpiredResetPasswordException();
		} else {
			$userDao = new UserDao($this->application);
			$user = $userDao->findByUserId($resetPassword->getUserId());
			if (is_null($user)){
				throw new InvalidResetPasswordException();
			} else {
				return $user;
			}
		}
	}
	
	public function redeemResetPasswordCode($userId, $code){
		$resetPasswordDao = new ResetPasswordDao($this->application);
		$redeemed = true;
		return $resetPasswordDao->updateCode($userId, $code, $redeemed);
	}
	
	// return true on success
	// return false on failure
	private function isResetPasswordCodeValid(ResetPassword $resetPassword){
		return ((strtotime($resetPassword->getExpiry()) < strtotime("now")) && (!$resetPassword->isRedeemed()));
	}
}

?>
