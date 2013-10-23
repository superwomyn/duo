<?php

abstract class BaseUrlService extends BaseService {

	protected $serviceName = "BaseUrlService";

	protected $applicationHostname;
	protected $staticHostname;
	
	private $cssPath;
	private $imgPath;
	private $jsPath;
	private $fontPath;
	
	function __construct($serviceManager) {
		parent::__construct($serviceManager);

		$context = $this->application->getApplicationContext();
		
		$this->applicationHostname = $context->getProperty("application_hostname");
		$this->staticHostname = $context->getProperty("static_hostname");
		$this->imgPath = $context->getProperty("application_img_path");
		$this->cssPath = $context->getProperty("application_css_path");
		$this->jsPath = $context->getProperty("application_js_path");
		$this->fontPath = $context->getProperty("application_font_path");
	}
	
	function __destruct(){
		parent::__destruct();
	}
	
	protected function getServiceName(){
		return $this->serviceName;
	}
	
    public function getHttpProtocol(){
		return "//";
    }

    public function getStaticHostnamePrefix(){
        return $this->staticHostname . "/";
    }

    public function getCSSPathPrefix(){
        return $this->cssPath . "/";
    }
    
    public function getImagePathPrefix(){
        return $this->imgPath . "/";
    }

    public function getJsPathPrefix(){
        return $this->jsPath . "/";
    }
    
    public function getFontPathPrefix(){
    	return $this->fontPath . "/";
    }
    
    public function getCssUrl(){
        $url = $this->getHttpProtocol();
        $url .= $this->getStaticHostnamePrefix();
        $url .= $this->getCSSPathPrefix();
        return $url;
    }
    
    public function getImageUrl(){
        $url = $this->getHttpProtocol();
        $url .= $this->getStaticHostnamePrefix();
        $url .= $this->getImagePathPrefix();
        return $url;
    }
    
    public function getJsUrl(){
        $url = $this->getHttpProtocol();
        $url .= $this->getStaticHostnamePrefix();
        $url .= $this->getJsPathPrefix();
        return $url;
    }
    
    public function getFontUrl(){
    	$url = $this->getHttpProtocol();
    	$url .= $this->getStaticHostnamePrefix();
    	$url .= $this->getFontPathPrefix();
    	return $url;
    }
}