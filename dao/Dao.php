<?php

class Dao  {

	private $application;
    protected $logger;
	protected $conn;
	protected $cache;
	protected $cacheKey;

	function __construct($application) {
		$this->application = $application;

		$context = $this->application->getApplicationContext();
		$this->conn = DB::getConnection($context);

		$cacheEnabled = $context->getProperty("cache_enabled");
		
		if ($cacheEnabled) {
			$this->cache = Cache::getCache($context);
		} else {
			$this->cache = null;
		}
		
		$this->logger = new ApplicationLogger("Dao", $this->application);
	}

	public function begin(){
		mysql_query("START TRANSACTION", $this->conn);
		mysql_query("BEGIN", $this->conn);
	}

	public function commit(){
		mysql_query("COMMIT", $this->conn);
	}

	public function rollback(){
		mysql_query("ROLLBACK", $this->conn);
	}
	
	protected function getFromCache($key){
	    $obj = null;
	    if (!is_null($this->cache)) {
			$obj = $this->cache->get($this->cacheKey . $key);
		}
		return $obj;
	}

	protected function addToCache($key, $value){
		if (!is_null($this->cache)) {
			$this->cache->set($this->cacheKey . $key, $value, MEMCACHE_COMPRESSED, 0);
		}
	}
	
	protected function deleteFromCache($key){
		if (!is_null($this->cache)) {
			$this->cache->delete($this->cacheKey . $key);
		}
	}
	
	protected function logQuery($class, $query){
	    $this->logger->debug($class. "\t" . $query);
	}
	
	protected function escapeBoolean($val){
	    $val = mysql_real_escape_string($val);
	    if(!empty($val)){
	        return 1;
	    } else {
	        return 0;
	    }
	}

}
?>