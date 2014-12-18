<?php

namespace VideoHoster\ServiceManager\AbstractFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ControllerAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if a controller class can be instantiated
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator,
                                             $name, $requestedName)
    {
        if (fnmatch('*Controller*', $requestedName) &&
            class_exists($requestedName . 'Controller')) {
            return true;
        }

        return false;
    }

    /**
     * Instantiate the controller class
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator,
                                          $name, $requestedName)
    {
        $controllerName = $requestedName . 'Controller';
        $controller = null;

        if (fnmatch('*Videos', $requestedName)) {
            $sm = $serviceLocator->getServiceLocator();
            $cache = array();

            if ($sm->has('Cache\Persistence')) {
                $cache['Cache\Persistence'] = $sm->get('Cache\Persistence');
            }

            if ($sm->has('Cache\Transient')) {
                $cache['Cache\Transient'] = $sm->get('Cache\Transient');
            }

            $controller = new $controllerName(
                $sm->get('VideoHoster\Tables\VideoTable'),
                $sm->get('VideoHoster\Tables\StatusTable'),
                $sm->get('VideoHoster\Tables\AuthorTable'),
                $sm->get('VideoHoster\Tables\LevelTable'),
                $sm->get('VideoHoster\Tables\PaymentRequirementTable'),
                $cache
            );
        }

        return $controller;
    }
} 