<?php

abstract class ApplicationView implements Componentable, Themeable, Localizable {

    private $id;
    private $data;
    private $components;
	private $requestUri;
	
	private $cssUrl;
	private $imageUrl;
	private $fontUrl;
	private $jsUrl;
	private $metaKeywords;
    private $metaDescription;
	private $metaAuthor;
	private $metaDesigner;
	private $cannonicalUrl;
	private $googleWebPropertyId;
	private $kissMetricsApiKey;
	
	private $bundle;
	

    function __construct($data) {
        $this->data = $data;
		$this->components = array();
        $this->requestUri = $_SERVER['REQUEST_URI'];
        
		$this->cssUrl = $data["cssUrl"];
		$this->imageUrl = $data["imageUrl"];
		$this->jsUrl = $data["jsUrl"];
		$this->fontUrl = $data["fontUrl"];
		
		$this->metaKeywords = $data["metaKeywords"];
		$this->metaDescription = $data["metaDescription"];
		$this->metaAuthor = $data["metaAuthor"];
		$this->metaDesigner = $data["metaDesigner"];
		$this->cannonicalUrl = $data["cannonicalUrl"];
		
		$this->googleWebPropertyId = $data["googleWebPropertyId"];
		$this->kissMetricsApiKey = $data["kissMetricsApiKey"];
		
		$this->bundle = $data["bundle"];
		
    }

    public function getId() {
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
    }

    public function getData() {
        return $this->data;
    }
    
	public function getCssUrl(){
	    return $this->cssUrl;
	}
	
	public function getImageUrl(){
	    return $this->imageUrl;
	}
	
	public function getFontUrl(){
		return $this->fontUrl;
	}
	
	public function getJsUrl(){
	    return $this->jsUrl;
	}
	
	public function getMetaKeywords(){
	    return $this->metaKeywords;
	}
	
    public function getMetaDescription(){
	    return $this->metaDescription;
	}
	
	public function getMetaAuthor(){
	    return $this->metaAuthor;
	}
	
	public function getMetaDesigner(){
	    return $this->metaDesigner;
	}
	
	public function getCannonicalUrl(){
	    return $this->cannonicalUrl;
	}
	
	public function getGoogleWebPropertyId(){
	    return $this->googleWebPropertyId;
	}
	
	public function getKissMetricsApiKey(){
		return $this->kissMetricsApiKey;
	}
	
	public function getBundle(){
		return $this->bundle;
	}
	
	public function getRequestUri(){
		return $this->requestUri;
	}
	
	abstract public function display();
	
	abstract public function loggedIn(); 
	
    public function getComponents() {
        return $this->components;
    }
    
	public function addComponents($components){
    	$oldArray = $this->components;
    	$oldKeys = array_keys($oldArray);
    	
   		$newArray = $components;
   		$newKeys = array_keys($newArray);
   		
   		foreach($newKeys as $newKey){
   			if (array_key_exists($newKey, $oldArray)){
   				echo "Duplicate Key in Component Array: " . $newKey . "<br/>";
   				exit;
   			}
   			$newValue = $newArray[$newKey];
   			$oldArray[$newKey] = $newValue;	
   		}
		$this->components = $oldArray;
   	}
   	
    public function getSupportedThemes() {
        return $this->supportedThemes;
    }
    
	public function setSupportedThemes($supportedThemes){
    	$this->supportedThemes = $supportedThemes;
   	}
   	
   	// TODO move stuff like this into some sort of UI utility class
   	public function getCacheBustingUrl($type, $path){
   		$parts = explode('.', $path);
   		$url = $parts[0];
   		$basePath = "https:";
   		if ($type == 'css'){
   			$basePath .= $this->getCssUrl();
   		} else if ($type == 'js'){
   			$basePath .= $this->getJsUrl();		
   		}
		$hashableFile = $basePath . $path;
   		if (file_exists($hashableFile)){
   			$url .= '-';
   			//    		$url .= hash_file('md5', .  . $parts[0]);
   			$url .= '.' . $parts[1];
   			return $url;
   		} else {
   			return $hashableFile;
   		}
   	}
   	
//    	public function getCacheBustingCSSUrl($filename){
//    		$parts = explode('.', $filename);
//    		$url = $this->getCssUrl();
//    		$url .= $parts[0];
//    		$url .= '-';
//    		$url .= hash_file('md5', 'https:' . $this->getCssUrl() . $parts[0]);
//    		$url .= '.css';
//    		$url = $this->getCssUrl() . $filename;
//    		return $url;
//    	}
   	
//    	public function getCacheBustingJSUrl($path, $filename){
//    		$parts = explode('.', $filename);
//    		$url = $this->getJsUrl();
//    		if (!empty($path)){
// 	   		$url .= $path;
// 	   		$url .= '/';
//    		}
//    		$url .= $parts[0];
//    		$url .= '-';
//    		if (!empty($path)){
//    			$url .= hash_file('md5', 'https:' . $this->getJsUrl() . $path . "/" . $parts[0]);
//    		} else {
//    			$url .= hash_file('md5', 'https:' . $this->getJsUrl() . $parts[0]);
//    		}   			
//    		$url .= '.js';
// 		$url = $this->getJsUrl() . $filename;
//    		return $url;
//    	}
   	
}

?>