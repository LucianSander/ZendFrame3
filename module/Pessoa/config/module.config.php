<?php

namespace Pessoa;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
 //           Controller\PessoaController::class => InvokableFactory::class,
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'pessoa' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/pessoa[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PessoaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
    'db' => [
        'driver' => 'Pdo_Pgsql',
        'database' => 'pessoa',
        'username' => 'postgres',
        'password' => '123456',
        'hostname' => 'localhost',
        'post' => '5432',
    ],
];