<?php 

class CodeTimer {
	
    private $startTime;
    private $startMemory;
    private $stopTime;
    private $stopMemory;
    
    public function start() {
    	$this->startTime = microtime(true);
		$this->startMemory = memory_get_usage();
    }
    
    public function stop() {
	    $this->stopTime = microtime(true);
		$this->stopMemory = memory_get_usage();	    
    }
    
    public function getElapsedTime(){
		return $this->stopTime - $this->startTime;
    }
    
    public function getMemoryUsage(){
	    return $this->stopMemory - $this->startMemory;
    }

}

?>