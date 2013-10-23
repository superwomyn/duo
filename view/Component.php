<?php

abstract class Component implements Displayable, Viewable, Localizable {
    
    private $id;
    private $view;
    
    public function __construct($view){
        $this->view = $view;
    }
    
	public function getId() {
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getView(){
	    return $this->view;
	}
	
	public function setView($view){
	    $this->view = $view;
	}
	
	public function getThemeKey(){
		return $this->getView()->getThemeKey();
	}
	
	public function getBundle(){
		return $this->getView()->getBundle();
	}
	
	public function getUris(){
		return $this->getView()->getUris();
	}
	
}