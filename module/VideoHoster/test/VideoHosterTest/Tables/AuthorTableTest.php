<?php
namespace VideoHosterTest\Tables;

use VideoHoster\Models\AuthorModel;
use VideoHoster\Tables\AuthorTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class AuthorTableTest extends PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    public function tearDown()
    {
        m::close();
    }

    public function testCanFetchSelectList()
    {
        $this->markTestIncomplete("This needs to be revisted");

        $recordData = array();
        $level = new AuthorModel();
        $level->exchangeArray($recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($level);
        $resultSet->initialize(array($level));

        // create the sql object
        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);
        $mockSql->shouldReceive('columns')
            ->with(array('user_id', 'display_name'))
            ->andReturn($mockSql);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
            ->times(1)
            ->with($mockSql)
            ->andReturn($resultSet);

        $AuthorTable = new AuthorTable($mockTableGateway);
        $AuthorTable->getSelectList($level);
    }
}
