<?php
namespace VideoHoster\ServiceManager\AbstractFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;
use VideoHoster\Models\VideoModel;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator, $name, $requestedName
    )
    {
        if (fnmatch('*TableGateway', $requestedName)) {
            return true;
        }

        return false;
    }

    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator, $name, $requestedName
    )
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

        /**
         * This is a simple way of instantiating the tablegateway classes. It's fine for now
         * but makes the assumption that they're all instantiated the same way. This could
         * potentially be done better by using a factory pattern. I'll likely switch to that
         * in a later release.
         */
        switch ($requestedName) {
            case ('VideoHoster\Tables\VideoTableGateway'):
            default:
                $hydrator = new ObjectProperty();
                $rowObjectPrototype = new VideoModel();
                $resultSet = new HydratingResultSet($hydrator, $rowObjectPrototype);
                return new TableGateway('tblvideo', $dbAdapter, null, $resultSet);
        }
    }
}
