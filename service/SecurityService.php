<?php

class SecurityService extends BaseService {

	protected $serviceName = "SecurityService";

	function __construct($serviceManager) {
		parent::__construct($serviceManager);
	}

	function __destruct(){
		parent::__destruct();
	}
	
	protected function getServiceName(){
		return $this->serviceName;
	}

	public function isPasswordValidForPrincipal($principal, $password){
		return (!is_null($this->authenticateUserByEmailAndPassword($principal->getEmail(), $password)));		
	}
	
	// return user on success
	// return null on failure
	private function authenticateUserByEmailAndPassword($email, $password){
		$userDao = new UserDao($this->application);
		$user = $userDao->findUserByEmailAndPassword($email, $password);
		if (!is_null($user) && $user->isDeleted()){
			return null;
		}
		return $user;
	}
	
	// return user on success
	// return null on failure
	private function authenticateUserBySessionKey($sessionKey){
		$sessionDao = new SessionDao($this->application);
		$session = $this->findValidSessionBySessionKey($sessionKey);
		if (!is_null($session) && !$this->isSessionExpired($session)){
			$userDao = new UserDao($this->application);
			$user = $userDao->findByUserId($session->getUserId());
			if ($user->isDeleted()){
				return null;
			}
			if($session->getUserId() == $user->getId()){
				$user->setSession($session);
			}
			return $user;
		} else {
			return null;
		}
		
	}
	
	// return true on success
	// return false on failure
	private function isSessionExpired($session){
		$now = time(); // seconds since epoch
		//$sessionTTL = 1800; // 1/2 hour
		return strtotime($session->getExpiry()) < $now;
	}
	
	// return user w/session key and session expiry on success
	// return null on failure
	public function login($email, $password){
		// is user already logged in?
		$sessionKey = $_COOKIE['duo_session'];
		$user = $this->authenticateUserBySessionKey($sessionKey);
		if (!is_null($user)) {
			// update session expiry, cookie expiry and return user
			$sessionDao = new SessionDao($this->application);
			$session = $sessionDao->updateSessionExpiry($user->getSession()->getId());
			$user->setSession($session);
//			$this->setSessionCookieByUser($user, $path);
			return $user;
		
		} else {
			$user = $this->authenticateUserByEmailAndPassword($email, $password);
			if (!is_null($user)){
				// create new session 
				$sessionDao = new SessionDao($this->application);
				$ipAddress = $_SERVER["REMOTE_ADDR"];
				$session = $sessionDao->createSession($user, $ipAddress);
				if($session->getUserId() == $user->getId()){
					$user->setSession($session);
//					$this->setSessionCookieByUser($user, $path);
				}
				return $user;	
			} else {
				return null;
			}
		}
	}

	
	// return session on success
	// return null on failure
	private function findSessionByUser($user){
		$sessionDao = new SessionDao($this->application);
		return $sessionDao->findSesssionByUserId($user->getUserId()); 
	}
	
	// return session on success
	// return null on failure
	private function findSessionByEmail($email){
		$sessionDao = new SessionDao($this->application);
		return $sessionDao->findSesssionByEmail($email); 
	}
	
	public function logout(User $user, $path){
		// invalidate all sessions for the user
   		$sessionDao = new SessionDao($this->application);
   		$sessionDao->invalidateAllSessionsForUserId($user->getId());
   		$this->unsetSessionCookie($path);
	}
	
	public function findValidSessionBySessionKey($sessionKey){
		$sessionDao = new SessionDao($this->application);
		$session = $sessionDao->findBySessionKey($sessionKey);
		if (!is_null($session) && $session->isValid()){
			return $session;
		} else {
			return null;
		}
	}
	
	public function setSessionCookieByUser(User $user, $path){
		$domain =  $_SERVER["SERVER_NAME"];
		setcookie("duo_session", $user->getSession()->getSessionKey(), strtotime($user->getSession()->getExpiry()), $path, $domain);
	}
	
	public function unsetSessionCookie($path){
		$domain = $_SERVER["SERVER_NAME"];
		setcookie("duo_session", "", time()-3600, $path,  $domain);
		unset($_COOKIE["duo_session"]);
	}
	
}


?>