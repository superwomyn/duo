<?php

abstract class BaseService {

	protected $serviceManager;
	protected $application;
	protected $logger = null;
	
	function __construct($serviceManager) {
		$this->serviceManager = $serviceManager;
		$this->application = $serviceManager->getApplication();
	}
	
	function __destruct(){
		if (!is_null($this->logger)){
			$this->logger->__destruct();
		}
		unset($this->logger);
	}
	
	public function getLogger(){
		if (is_null($this->logger)){
			$this->logger = new ApplicationLogger($this->getServiceName(), $this->application);
		}
		return $this->logger;
	}
	
	abstract protected function getServiceName();
	
}