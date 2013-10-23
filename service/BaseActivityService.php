<?php

abstract class BaseActivityService extends BaseService {

	protected $serviceName = "BaseActivityService";
	
	function __construct($serviceManager) {
		parent::__construct($serviceManager);
	}

	function __destruct(){
		parent::__destruct();
	}
	
	protected function getServiceName(){
		return $this->serviceName;
	}

	public function logActivity(Activity $activity){
		$activityDao = new ActivityDao($this->application);
		$activityDao->createActivity($activity);
	}	
	
 	public function findRecentActivity($type = null){
	 	$activityDao = new ActivityDao($this->application);
		$activities = $activityDao->findRecentActivity(100, 0, $type); // limit, offset
		if (!is_null($activities)){
			foreach($activities as $activity){
				$readable = $this->getReadableActivity($activity);
				$activity->setReadable($readable);
			}
		}
		return $activities;
 	}

 	abstract protected function getReadableActivity($activity);
 		
}


?>