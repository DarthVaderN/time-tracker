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
        'controller' => 'profile',
    ],
    '/users' => [
        'controller' => 'users',
    ]
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
