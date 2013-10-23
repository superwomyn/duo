<?php

class Cache {

	//$startTime = microtime(true);
	//$endTime = microtime(true);
	//echo "<br>Elapsed Time: " . round(($endTime - $startTime),5,PHP_ROUND_HALF_UP);

	public static function getCache($applicationContext){
	
		$cacheEnabled = $applicationContext->getProperty("cache_enabled");
		$cacheHostname = $applicationContext->getProperty("cache_hostname");
		$cachePort = $applicationContext->getProperty("cache_port");

		if ($cacheEnabled) {
			$cache = new Memcache;
			try {
				$cache->connect($cacheHostname, $cachePort) or new Exception("Cannot connect to Memcached.");
			} catch (Exception $e) {
				$cache = null;
			}
		}
		return $cache;
	}
}

?>