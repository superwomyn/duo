<?php

class ClassLoader {
    
    public function __construct() {
        spl_autoload_register(array($this, 'doItLive'));
        spl_autoload_register(array($this, 'doNamespacesLive'));
    }

    function doItLive($className) {
        $paths = $GLOBALS["CLASS_LOADER_PATHS"];
        foreach ($paths as $path) {
            $file = $path . "/" . $className . ".php";
            //echo "Looking for file: " . $file . "<Br>";
            if (file_exists($file)) {
            	//echo "=====>Found file: " . $file . "<Br>";
                require_once($file);
                break;
            }
        }
    }
    
	function doNamespacesLive($className) {
		//echo "Looking for className: " . $className . "<Br>";
		$paths = $GLOBALS["CLASS_LOADER_NAMESPACE_PATHS"];
		foreach ($paths as $path) {
            $file = $path . "/" . str_replace('\\', '/', $className) . '.php';
           	 //echo "Looking for file: " . $file . "<Br>";
            if (file_exists($file)) {
            	//echo "=====>Found file: " . $file . "<Br>";
                require_once($file);
                break;
            }
        }
	}

}
?>