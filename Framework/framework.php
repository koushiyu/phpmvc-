<?php

namespace  Framework;
class framework
{
    public function __construct($config)
    {
        define('HOST', $config['host']);
        define('PORT', $config['port']);
        define('DBMS', $config['dbms']);
        define('DB_NAME', $config['dbName']);
        define('DB_USER', $config['dbUser']);
        define('DB_PASS', $config['dbPass']);
        define('DB_HOST', $config['dbHost']);
        define('ROOT',__DIR__.'/../');
        define('APP_PATH', ROOT.'App/');
        define('FRAMEWORK_PATH', ROOT.'Framework/');
        echo "
			     ┏┛ ┻━━━━━┛ ┻┓
			     ┃　　　　　　 ┃
			     ┃　　　━　　　┃
			     ┃　┳┛　  ┗┳　┃
			     ┃　　　　　　 ┃
			     ┃　　　┻　　　┃
			     ┃　　　　　　 ┃
			     ┗━┓　　　┏━━━┛
			        ┃　　　┃   神兽保佑
			        ┃　　　┃   代码无BUG！
			        ┃　　　┗━━━━━━━━━┓
			        ┃　　　　　　　    ┣┓
			        ┃　　　　         ┏┛
			        ┗━┓ ┓ ┏━━━┳ ┓ ┏━┛
			         ┃ ┫ ┫   ┃ ┫ ┫
			         ┗━┻━┛   ┗━┻━┛
		";
        $this->Auto_Require();
    }

    public function run()
    {
        $http = new \Swoole\Http\Server(HOST, PORT);
        $http->set([
            'enable_static_handler' => true,
            'document_root' => ROOT. 'static',
        ]);
        $http->on('request', [$this, 'request']);
        $http->start();
    }

    private function Auto_Require()
    {
        require(FRAMEWORK_PATH .'base/Controller.php');
        require(FRAMEWORK_PATH .'base/Model.php');
        require(FRAMEWORK_PATH .'base/View.php');
        foreach (scandir(APP_PATH . 'Controller') as $value) {
            if ($value == '.' or $value == '..') continue;
            require(APP_PATH . 'Controller/' . $value);
        }
        foreach (scandir(APP_PATH . 'Model') as $value) {
            if ($value == '.' or $value == '..') continue;
            require(APP_PATH . 'Model/' . $value);
        }
        foreach (scandir(APP_PATH . 'View') as $value) {
            if ($value == '.' or $value == '..') continue;
            require(APP_PATH . 'View/' . $value);
        }
    }

    public function request($request, $response)
    {
        $this->Route($request,$response);
    }

    private function Route($request, $response)
    {
        $Path = $request->server['path_info'];
        $Path = ltrim($Path, '/');
        $Path = explode('/', $Path);
        if ($Path[0] == "") {
            $response->status(302);
            $response->end('<script>window.location.href="/index"</script>');
            return;
        } else {
            $control = 'app\controller\\' . $Path[0] . 'Controller';
            $control = class_exists($control) ? $control : '\app\controllers\indexController';
        }
        $controller = new $control($request, $response);
        if (is_null($Path[1])) {
            $controller->index();
        } elseif (method_exists($control, $Path[1])) {
            $controller->{$Path[1]}();
        } else {
            $controller->index();
        }
    }
}