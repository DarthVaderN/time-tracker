<?php
use Phalcon\Mvc\Router;
$router = $di->getRouter();
$router->setDefaults([
    'namespace' => 'Controller',
    'controller' => 'index',
    'action' => 'index',
]);

$groups = [
    '/profile' => [
        'namespace' => 'Controller\\Profile',
        'controller' => 'profile',
    ],
    '/products' => [
        'namespace' => 'Controller\\Products',
        'controller' => 'products',
    ],
    '/categories' => [
        'namespace' => 'Controller\\Categories',
        'controller' => 'categories',
    ],
];

foreach ($groups as $prefix => $groupDefinition) {
    $groupRouter = new Router\Group($groupDefinition);

    $groupRouter->setPrefix($prefix);

    $groupRouter->addGet('/', [
        'action' => 'index',
    ]);

    $groupRouter->addGet('/{id:[0-9]+}', [
        'action' => 'view',
    ]);

    $groupRouter->addGet('/{id:[0-9]+}/edit', [
        'action' => 'edit',
    ]);

    $groupRouter->addGet('/{id:[0-9]+}/delete', [
        'action' => 'delete',
    ]);

    $groupRouter->addPost('/save', [
        'action' => 'save',
    ]);

    $router->mount($groupRouter);
}



$router->handle();
