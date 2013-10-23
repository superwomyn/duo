<?php
 
class Request {

	private $data;
	
	function __construct() { 
		$this->data = array();
    }
    	
	public function getData(){
		return $this->data;
	}
	
    public function addData($data){
        $oldArray = $this->data;

        $newArray = $data;
        $newKeys = array_keys($newArray);

        foreach($newKeys as $newKey){
            if (array_key_exists($newKey, $oldArray)){
                echo "Duplicate Key in Data Array: " . $newKey . "<br/>";
                exit;
            }
            $newValue = $newArray[$newKey];
            $oldArray[$newKey] = $newValue;

        }
        $this->data = $oldArray;
    }

    public function resetData(){
        $this->data = array();
    }
}

?>
