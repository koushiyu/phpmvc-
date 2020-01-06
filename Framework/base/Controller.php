<?php

    namespace framework\base;
    use app\views\errorView;
    abstract class Controller
    {

        protected $response = null;
        protected $request = null;
        public function __construct($request,$response)
        {
            $this->response = $response;
            $this->request = $request;
            $this->__init();
        }


        abstract function index();
        
    	public function __init(){}
    	
    	public function error(){
    		$this->response->status(404);
        	(new errorView($this->response))->view();
    	}
    }
