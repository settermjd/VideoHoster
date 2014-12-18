<?php
namespace VideoHosterTest\Tables;

use VideoHoster\Models\LevelModel;
use VideoHoster\Tables\LevelTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class LevelTableTest extends PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    public function tearDown()
    {
        m::close();
    }

    public function testCanFetchSelectList()
    {
        $recordData = array(

        );
        $level = new LevelModel();
        $level->exchangeArray($recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($level);
        $resultSet->initialize(array($level));

        // create the sql object
        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);
        $mockSql->shouldReceive('columns')
            ->with(array('levelId', 'name'))
            ->andReturn($mockSql);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
            ->times(1)
            ->with($mockSql)
            ->andReturn($resultSet);

        $LevelTable = new LevelTable($mockTableGateway);
        $LevelTable->getSelectList($level);
    }
}
