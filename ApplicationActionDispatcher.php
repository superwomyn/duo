<?php

abstract class AbstractApplicationActionDispatcher {

	private $application;
	
	protected $action;
	protected $method;
	protected $response;
	protected $request;
	
	function __construct($application, $post, $get) {
        
	    $this->application = $application;

		$this->action = $post["action"];
		if (empty($this->action)){
			$this->action = $get["action"];
		}
		$this->action .= "Action";
		
		$this->method = $post["method"];
		if (empty($this->method)){
			$this->method = $get["method"];
		}
		$this->response = $post["response"];
		if (empty($this->response)){
			$this->response = $get["response"];
		}
		$this->response .= "View";
		
		$this->request = new Request();
		$this->request->addData(array("post"=>$_POST, "get"=>$_GET));		
	}
	
	abstract protected function doAction();
	
	protected function doMethod($action, $method, $response){
	    $request = null;
	    switch($method){
            case "get":
				$request = $action->doGet();
				break;
			case "post":
                $request = $action->doPost();
				break;
			case "put":
                $request = $action->doPut();
				break;
			case "delete":
                $request = $action->doDelete();
				break;
		}
		$data = $request->getData();

		$redirect = $data["redirect"]; // server-side redirect
		if (!empty($redirect)){
		    header("Location: " . $redirect);
		} 
		
		$output = $data["output"];
		if (!empty($output) && $output == "json"){
        	die(json_encode($request->getData()));
		}
		
		$view = new $response($request->getData());
		return $view;
	}
	
	public function getApplication(){
	    return $this->application;
	}
	
}

class ApplicationActionDispatcher extends AbstractApplicationActionDispatcher {
    
	function __construct($application, $post, $get) {
		parent::__construct($application, $post, $get);
	}

	public function doAction(){
		$this->getApplication()->getLogger()->debug("Action: " . $this->action . ", Method: " . $this->method);
		$action = new $this->action($this->getApplication(), $this->request);

		// if somewhere in the constructor chain we decided that we needed to redirect, 
		// lets do that before we do anything else!
		
		$data =  $this->request->getData();
		$redirect = $data["redirect"];
		if (!empty($redirect)){
		    header("Location: " . $redirect);
		} 
		
		$view = $this->doMethod($action, $this->method, $this->response);
		if (!empty($view)){
		    $view->display();
		}
	}
}

?>