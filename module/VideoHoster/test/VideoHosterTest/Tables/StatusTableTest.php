<?php
namespace VideoHosterTest\Tables;

use VideoHoster\Models\StatusModel;
use VideoHoster\Tables\StatusTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class StatusTableTest extends PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    public function tearDown()
    {
        m::close();
    }

    public function testCanFetchSelectList()
    {
        $recordData = array();
        $level = new StatusModel();
        $level->exchangeArray($recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($level);
        $resultSet->initialize(array($level));

        // create the sql object
        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);
        $mockSql->shouldReceive('columns')
            ->with(array('statusId', 'name'))
            ->andReturn($mockSql);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
            ->times(1)
            ->with($mockSql)
            ->andReturn($resultSet);

        $StatusTable = new StatusTable($mockTableGateway);
        $StatusTable->getSelectList($level);
    }
}
