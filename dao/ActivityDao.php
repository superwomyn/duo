<?php

class ActivityDao extends Dao {

	protected $cacheKey = "Activity::";

    function __construct($application){
	    parent::__construct($application);
	}
	
	/* ---- READ OPS ---- */
	public function findByActivityId($activityId){
		$activity = $this->getFromCache($activityId);
		if ($activity){
			return $activity;
		} else {
			$queryStr = "select id, user_id, object, object_id, type, note, date_created, date_updated ";
			$queryStr .= "from activity where id = %s";
			$query = sprintf($queryStr,
			mysql_real_escape_string($activityId));
			$this->logQuery("", $query);
			$result = mysql_query($query);
			if (!$result || mysql_num_rows($result) == 0)  {
				return null;
			}
			$activity = new Activity();
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$activity->setId($row["id"]);
				$activity->setUserId($row["user_id"]);
				$activity->setObject($row["object"]);
				$activity->setObjectId($row["object_id"]);
				$activity->setType($row["type"]);
				$activity->setNote($row["note"]);
				$activity->setDateCreated($row["date_created"]);
				$activity->setDateUpdated($row["date_updated"]);
			}
			mysql_free_result($result);
			$this->addToCache($activity->getId(), $activity);
			return $activity;
		}
	}
	

	/* ---- WRITE OPS ---- */
	public function createActivity(Activity $activity){
	    $now = date("Y-m-d H:i:s");
	    $queryStr = "insert into activity (user_id, object, object_id, type, note, date_created, date_updated) ";
		$queryStr .= "values (%s, '%s', %s, '%s', '%s', '%s', '%s') ";
		$query = sprintf($queryStr,
		mysql_real_escape_string($activity->getUserId()),
		mysql_real_escape_string($activity->getObject()),
		mysql_real_escape_string($activity->getObjectId()),
		mysql_real_escape_string($activity->getType()),
		mysql_real_escape_string($activity->getNote()),
		mysql_real_escape_string($now),
		mysql_real_escape_string($now));
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result)  {
			return null;
		}
		$activityId = mysql_insert_id();
		$activityFromDb = $this->findByActivityId($activityId);
		return $activityFromDb;  
	}
	
	/* ---- REPORTING OPS ---- */
	public function findRecentActivity($limit, $offset, $type){
		$queryStr = "select id ";
		$queryStr .= "from activity ";
		if (!empty($type)){
			$queryStr .= "where type = '%s' ";
		}
		$queryStr .= "order by date_created desc ";
		$queryStr .= "limit %s,%s";
		if (!empty($type)){
			$query = sprintf($queryStr,
			mysql_real_escape_string($type),
			mysql_real_escape_string($offset),
			mysql_real_escape_string($limit));
		} else {
			$query = sprintf($queryStr,
			mysql_real_escape_string($offset),
			mysql_real_escape_string($limit));
		}
		
		$this->logQuery("", $query);
		$result = mysql_query($query);
		if (!$result || mysql_num_rows($result) == 0)  {
			return null;
		}
		$activities = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$activity = $this->findByActivityId($row["id"]);
			array_push($activities, $activity);
		}
		mysql_free_result($result);
		return $activities;
	}

}
?>