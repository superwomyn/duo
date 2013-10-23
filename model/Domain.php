<?php

abstract class Domain {
	
	protected $id;
	protected $dateCreated;
	protected $dateUpdated;
	
	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
		return $this;
	}
	
	public function getDateCreated(){
		return $this->dateCreated;
	}
	public function setDateCreated($dateCreated){
		$this->dateCreated = $dateCreated;
		return $this;
	}
	
	public function getDateUpdated(){
		return $this->dateUpdated;
	}
	public function setDateUpdated($dateUpdated){
		$this->dateUpdated = $dateUpdated;
		return $this;
	}
	
}
?>