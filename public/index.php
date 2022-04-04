<?php
// Start a Session
//if( !session_id() ) @session_start();

// Initialize Composer Autoload
require "../vendor/autoload.php";

use DI\ContainerBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;

$containerBuilder = new containerBuilder;
$containerBuilder->addDefinitions([
    Engine::class => function () {
        return new Engine('../app/views');
    },
    PDO::class => function () {
        $driver = "mysql";
        $host = "localhost";
        $database_name = "modul";
        $charset = "utf8";
        $username = "root";
        $password = "";

        return new PDO("$driver:host=$host;dbname=$database_name;charset=$charset", $username, $password);
    },

    Delight\Auth\Auth::class => function ($container) {
        return new Auth($container->get('PDO'));
    },

]);

$container = $containerBuilder->build();
// // Function
// flash()->message('Hot!', 'success');

// echo flash()->display();
// // require "../vendor/autoload.php";
// // d([1,2,3]);

// $templates = new League\Plates\Engine('../app/views');

// // echo $templates->render('homepage', ['name' => 'Tania']);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/home', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/register', ['App\controllers\RegisterController', 'index']);
    $r->addRoute('POST', '/register_handler', ['App\controllers\RegisterController', 'register']);
    $r->addRoute('GET', '/email_verify', ['App\controllers\RegisterController', 'email_verify']);
    $r->addRoute('GET', '/login', ['App\controllers\LoginController', 'index']);
    $r->addRoute('POST', '/login_handler', ['App\controllers\LoginController', 'login_handler']);
    $r->addRoute('GET', '/logout', ['App\controllers\LoginController', 'logout']);
    $r->addRoute('GET', '/create_user', ['App\controllers\UsersController', 'index']);
    $r->addRoute('POST', '/create_user_handler', ['App\controllers\UsersController', 'create_user']);
    $r->addRoute('GET', '/edit', ['App\controllers\UsersController', 'edit']);
    $r->addRoute('POST', '/update_user_info', ['App\controllers\UsersController', 'update_info_edit']);
    $r->addRoute('POST', '/update_security', ['App\controllers\UsersController', 'update_security']);
    $r->addRoute('GET', '/page_media', ['App\controllers\UsersController', 'media']);
    $r->addRoute('POST', '/upload_media', ['App\controllers\UsersController', 'upload_media']);
    $r->addRoute('GET', '/page_profile', ['App\controllers\UsersController', 'page_profile']);
    $r->addRoute('GET', '/security', ['App\controllers\UsersController', 'security']);
    $r->addRoute('GET', '/status', ['App\controllers\UsersController', 'status']);
    $r->addRoute('POST', '/update_status', ['App\controllers\UsersController', 'update_status']);
    $r->addRoute('GET', '/delete[/{id:\d+}]', ['App\controllers\UsersController', 'delete_user']);
//    $r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\HomeController', 'index']);
    // $r->addRoute('GET', '/about/{id:\d+}', ['App\controllers\HomeController', 'about']);
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404!";
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405!";
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], $routeInfo[2]);
        break;
}
