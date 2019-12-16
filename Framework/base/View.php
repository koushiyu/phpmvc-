<?php

    namespace framework\base;

    class View
    {

        protected $data;
        protected $response = null;
        public function __construct($response,$data = [])
        {
            $this->data = $data;
            $this->response = $response;
        }

        public function view(){
            // $this->response>end()
            $viewName =  rtrim(end(explode('\\',get_class($this))),'View');
            $this->response->end($GLOBALS['twig']->render($viewName.'.html.Twig',$this->data));
        }
    }