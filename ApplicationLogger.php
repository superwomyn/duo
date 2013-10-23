<?php

class ApplicationLogger {
	
	private $application;
	private $logger;
	private $filename;
	
	function __construct($filename, $application){
		$this->filename = $filename;
		$this->application = $application;
		
		$context = $this->application->getApplicationContext();
		$logDir = $context->getProperty("log_dir");
		$logLevel = $context->getProperty("log_level");
		
		$this->logger = new KLogger($logDir, $logLevel);
	}
	
	function __destruct(){
		
	}
	
	public function debug($msg){
		$this->logger->LogDebug($this->getCompleteMessage($msg));
	}

	public function info($msg){
		$this->logger->LogInfo($this->getCompleteMessage($msg));
	}
	
	public function warn($msg){
		$this->logger->LogWarn($this->getCompleteMessage($msg));
	}
	
	public function error($msg){
		$this->logger->LogError($this->getCompleteMessage($msg));
	}
	
	public function fatal($msg){
		$this->logger->LogFatal($this->getCompleteMessage($msg));
	}
	
	private function getCompleteMessage($msg){
		return $_SERVER["REMOTE_ADDR"] . "\t" . $this->filename . "\t" . $msg;
	}
	
}

?>