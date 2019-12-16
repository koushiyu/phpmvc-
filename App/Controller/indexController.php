<?php

namespace app\controllers;
use framework\base\Controller;

class indexController extends Controller
{
    public function index()
    {
        $this->response->end('框架已经启动');
        // TODO: Implement index() method.
    }
}