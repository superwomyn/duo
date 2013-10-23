<?php 

class I18n {
    
    private $properties;
    private $locale;
    
    public function __construct($locale){
        $this->locale = $locale;
        $this->properties = parse_ini_file($GLOBALS["I18N_DIR"]."/locale_".$locale.".properties", true);        
    }
    
    public function getProperties(){
        return $this->properties;
    }
    
    public function getLocale(){
        return $this->locale;
    }
    
}

?>