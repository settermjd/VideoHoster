<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 30/07/14
 * Time: 09:14
 */

namespace VideoHosterTest\Tables;

use VideoHoster\Models\VideoModel;
use VideoHoster\Tables\VideoTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class VideoTableTest extends PHPUnit_Framework_TestCase 
{
    protected $traceError = true;

    protected $_recordData =  array(
        'statusId'  => 1,
        'status'  => "Active"
    );

    public function tearDown()
    {
        m::close();
    }

    public function testFetchBySlug()
    {
        $resultSet = new ResultSet();
        $record = new VideoModel();
        $record->exchangeArray($this->_recordData);
        $slug = "zend-framework-security";
        $resultSet->initialize(array($record));

        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);
        $mockSql->shouldReceive('where')
                ->with(array('slug' => $slug))
                ->times(1)
                ->andReturn($mockSql);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
                         ->times(1)
                         ->with($mockSql)
                         ->andReturn($resultSet);

        $mockVideoTable = new VideoTable($mockTableGateway);

        $this->assertEquals($record, $mockVideoTable->fetchBySlug($slug));
    }


    public function testFetchAllActiveVideosByReverseDateOrder()
    {
        $resultSet = new ResultSet();
        $record = new VideoModel();
        $record->exchangeArray($this->_recordData);
        $resultSet->initialize(array($record));

        // create the sql object
        $mockSql = \Mockery::mock('Zend\Db\Sql\Select');
        $mockSql->shouldReceive('select')->andReturn($mockSql);

        // mock the where clause
        $mockSql->shouldReceive('where')
            ->with(array('tblstatus.name' => 'active'))
            ->times(1)
            ->andReturn($mockSql);

        // mock the join on the status table
        $mockSql->shouldReceive('join')
            ->with('tblstatus', 'tblstatus.statusId = tblvideo.statusId', array())
            ->times(1)
            ->andReturn($mockSql);

        // mock the order by clause
        $mockSql->shouldReceive('order')
            ->with('publishDate DESC')
            ->times(1)
            ->andReturn($mockSql);

        // get the sql object
        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('getSql')->andReturn($mockSql);
        $mockTableGateway->shouldReceive('selectWith')
            ->times(1)
            ->with($mockSql)
            ->andReturn($resultSet);

        $mockVideoTable = new VideoTable($mockTableGateway);

        $this->assertEquals($resultSet, $mockVideoTable->fetchActiveVideos());
    }

    /*
    public function testSaveWillInsertNewIfTheyDoNotAlreadyHaveAnId()
    {
        $status = new VideoModel();
        $recordData = $this->_recordData;
        unset($recordData['statusId']);
        $status->exchangeArray($recordData);

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false
        );
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with(array(
                'status'  => "Active",
            ));

        $VideoTable = new VideoTable($mockTableGateway);
        $VideoTable->save($status);
    }

    public function testSaveWillUpdateExistingIfTheyAlreadyHaveAnId()
    {
        $recordData = $this->_recordData;
        $statusId = $recordData['statusId'];
        unset($recordData['statusId']);
        $status = new VideoModel();
        $status->exchangeArray($this->_recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new VideoModel());
        $resultSet->initialize(array($status));

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('update')->with(array(
            'status'  => "Active",
        ), array('statusId' => $statusId))->andReturn($this->_recordData);

        $VideoTable = new VideoTable($mockTableGateway);
        $VideoTable->save($status, $statusId);
    }
    */
}
 