<?php
namespace VideoHoster\ServiceManager\AbstractFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TableAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator, $name, $requestedName
    ) {
        if (fnmatch('*Table', $requestedName)) {
            return true;
        }
        return false;
    }

    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator, $name, $requestedName
    ) {
        if (class_exists($requestedName)) {
            $tableGateway = $requestedName . 'Gateway';
            return new $requestedName($serviceLocator->get($tableGateway));
        }

        return false;
    }
}
