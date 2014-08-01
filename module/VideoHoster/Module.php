<?php
namespace VideoHoster;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(
                'VideoHoster\ServiceManager\AbstractFactory\TableAbstractFactory',
                'VideoHoster\ServiceManager\AbstractFactory\TableGatewayAbstractFactory'
            )
        );
    }
}
