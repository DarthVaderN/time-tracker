<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;
use Phalcon\Session\Factory;
use Phalcon\Session\Adapter\Files as Session;

$debug = new \Phalcon\Debug();
$debug->listen();

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();
$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
)->register();

// Create a DI
$di = new FactoryDefault();

// Setting up the view component
$di['view'] = function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/views/');
    return $view;
};

// Setup a base URI so that all generated URIs include the "tutorial" folder
$di['url'] = function () {
    $url = new UrlProvider();
    $url->setBaseUri('/timer/');
    return $url;
};

// Set the database service
$di->set(
    'db',
    function () {
        return new DbAdapter(
            [
                'host'     => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
                'port' => 8889,
                'dbname'   => 'timer',
            ]
        );
    }
);
// Сессии запустятся один раз, при первом обращении к объекту
$di->setShared(
    'session',
    function () {
        $session = new Session();

        $session->start();

        return $session;
    }
);
function print_arr( $var, $return = false )
{
    $type = gettype( $var );
    $out = print_r( $var, true );
    $out = htmlspecialchars( $out );
    $out = str_replace('  ', '&nbsp; ', $out );
    if( $type == 'boolean' )
        $content = $var ? 'true' : 'false';
    else
        $content = nl2br( $out );
    $out = '<div style="
  border:2px inset #666;
  background:black;
  font-family:Verdana;
  font-size:11px;
  color:#6F6;
  text-align:left;
  margin:20px;
  padding:16px">
   <span style="color: #F66">('.$type.')</span> '.$content.'</div><br /><br />';
    if( !$return )
        echo $out;
    else
        return $out;
}
function print_die($var, $return = false)
{
    print_arr($var, $return);
    die ();
}


// Handle the request
try {
    $application = new Application($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
