<?php 

class ApplicationContext {
    
    private $properties;
    private $environment;
    
    public function __construct(){
    	$cwd = getcwd();
		$propertiesFile = $cwd . "/.." . $GLOBALS["CONTEXT_SUBDIR"]."/".$GLOBALS["CONTEXT_APP"].".properties";
	    if (file_exists($propertiesFile)){
		    $this->properties = parse_ini_file($propertiesFile, true); 
	    } else {
	    	$propertiesFile = $GLOBALS["CONTEXT_DIR"]."/".$GLOBALS["CONTEXT_SUBDIR"]."/".$GLOBALS["CONTEXT_APP"].".properties";
		    $this->properties = parse_ini_file($propertiesFile, true); 
	    }
        $this->environment = $this->properties["context"]["environment"]; 
    }

	// deprecated    
    private function getProperties(){
        return $this->properties;
    }
    
    public function getEnvironment(){
    	return $this->environment;
    }
    
    public function getProperty($key){
    	return $this->properties[$this->environment][$key];
    }
    
}

?>