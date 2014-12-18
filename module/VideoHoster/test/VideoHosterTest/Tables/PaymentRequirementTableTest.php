<?php
namespace VideoHosterTest\Tables;

use VideoHoster\Models\PaymentRequirementModel;
use VideoHoster\Tables\PaymentRequirementTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class PaymentRequirementTableTest extends PHPUnit_Framework_TestCase
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
        $paymentRequirement = new PaymentRequirementModel();
        $paymentRequirement->exchangeArray($recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype($paymentRequirement);
        $resultSet->initialize(array($paymentRequirement));

        // create the sql object
        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);
        $mockSql->shouldReceive('order')->andReturn($mockSql);
        $mockSql->shouldReceive('columns')
            ->with(array('paymentRequirementId', 'name'))
            ->andReturn($mockSql);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
            ->times(1)
            ->with($mockSql)
            ->andReturn($resultSet);

        $paymentRequirementTable = new PaymentRequirementTable($mockTableGateway);
        $paymentRequirementTable->getSelectList($paymentRequirement);
    }
}
