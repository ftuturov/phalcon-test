<?php
    use Phalcon\Loader;
    use Phalcon\Mvc\View;
    use Phalcon\Mvc\Application;
    use Phalcon\Di\FactoryDefault;
    use Phalcon\Mvc\Router;

    define('BASE_PATH', dirname(__DIR__));
    define('APP_PATH', BASE_PATH . '/app');

    $loader = new Loader();

    $loader->registerDirs(
        [
            APP_PATH . '/controllers/',
            APP_PATH . '/models/',
        ]
    );

    $loader->register();

    $di = new FactoryDefault();

    $di->set(
        'view',
        function () {
            $view = new View();
            $view->setViewsDir(APP_PATH . '/views/');
            return $view;
        }
    );

    $di->set('router', function(){
        $router = new Phalcon\Mvc\Router(false);
        $router->add(
            "/",
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );
        $router->add(
            "/phalcon/public_html/hello",
            [
                "controller" => "hello",
                "action"     => "show",
            ]
        );
        $router->handle();
        return $router;
    });

    $application = new Application($di);

    try {
        $response = $application->handle();

        $response->send();
    } catch (\Exception $e) {
        echo 'Exception: ', $e->getMessage();
    }
