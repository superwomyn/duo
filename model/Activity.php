<?php

class Activity extends Domain {

	private $userId;
	private $type;
	private $object;
	private $objectId;
	private $note;
	
	// transient
	private $readable;

	public function getUserId(){
		return $this->userId;
	}
	public function setUserId($userId){
		$this->userId = $userId;
	}

	public function getType(){
		return $this->type;
	}
	public function setType($type){
		$this->type = $type;
	}
	
	public function getObject(){
		return $this->object;
	}
	public function setObject($object){
		$this->object = $object;
	}
	
	public function getObjectId(){
		return $this->objectId;
	}
	public function setObjectId($objectId){
		$this->objectId = $objectId;
	}

	public function getNote(){
		return $this->note;
	}
	public function setNote($note){
		$this->note = $note;
	}
	
	// transient
	public function getReadable(){
		return $this->readable;
	}
	
	public function setReadable($readable){
		$this->readable = $readable;
	}
}

?>