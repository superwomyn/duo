<?php

abstract class ApplicationAction {

    private $application;
    private $i18nProperties;
	protected $request;
	protected $data;
	private $logger;
	
	function __construct($application, $request) {
	    $this->application = $application;
	    $this->i18nProperties = $this->application->getI18n()->getProperties();  
		$this->request = $request;
		$this->data = $request->getData();
		$this->request->addData(array("env"=>$this->application->getApplicationContext()->getEnvironment()));
		$this->logger = new ApplicationLogger("ApplicationAction", $application);
    }

    public function getApplication(){
        return $this->application;
    }
    
    public function getI18nProperties(){
        return $this->i18nProperties;
    }
    
    public function getLogger(){
    	return $this->logger;
    }
    
    // FIXME do i belong somewhere else?
	public function sanitizeInput($input) {
	    return strip_tags(trim($input));
	}
}

?>