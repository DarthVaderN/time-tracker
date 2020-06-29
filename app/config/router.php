<?php

$router = $di->getRouter();

$router->setDefaults([
    'namespace' => 'Controller',
    'controller' => 'index',
    'action' => 'index',
]);

$router->handle();
