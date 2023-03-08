<?php

namespace Pessoa;

// Add these import statements:
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Pessoa\Controller\PessoaController;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    // getConfig() method is here

    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PessoaTable::class => function($container) {
                    $tableGateway = $container->get(Model\PessoaTableGateway::class);
                    return new Model\PessoaTable($tableGateway);
                },
                Model\PessoaTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Pessoa());
                    return new TableGateway('pessoa', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig(){
        return [
            'factories' => [
                Controller\PessoaController::class => function($container) {
                    return new Controller\PessoaController(
                        $container->get(Model\PessoaTable::class)
                    );
                },
            ],
        ];
    }
}