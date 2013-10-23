<?php

require_once("core/core/ClassLoader.php");

class Application {
    
    private $classLoader;
    private $actionDispatcher;
    private $applicationContext;
    private $i18n;
    private $logger;
    
    public function __construct(){
    }
    
    public function init($post, $get){
        $this->classLoader = new ClassLoader();
        $this->applicationContext = new ApplicationContext();
        $this->i18n = new I18n("en");
        $this->logger = new ApplicationLogger("Application", $this);
        $this->actionDispatcher = new ApplicationActionDispatcher($this, $post, $get);
        $this->actionDispatcher->doAction();
    }
    
    public function getApplicationContext(){
        return $this->applicationContext;
    }
    
    public function getI18n(){
        return $this->i18n;
    }
    
    public function getLogger(){
        return $this->logger;
    }
    
}

?>