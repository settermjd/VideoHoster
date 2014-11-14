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
        'videoId' => 1,
        'name'  => "zend framework security",
        'authorId'  => "1 Queen St",
        'statusId'  => "",
        'description'  => "Brisbane",
        'extract'  => "Qld",
        'duration'  => "4000",
        'publishDate'  => 61,
        'publishTime'  => 61,
        'levelId'  => 61,
        'paymentRequirementId' => 1,
        'slug'  => "zend-framework-security",
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
        $resultLimit = 15;

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
        $mockSql->shouldReceive('limit')
            ->with($resultLimit)
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

        $this->assertEquals($resultSet, $mockVideoTable->fetchActiveVideos($resultLimit));
    }

    public function testFetchAllActiveVideosByReverseDateOrderWithoutLimit()
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
        $mockSql->shouldReceive('limit')
            ->never()
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

    public function testFetchAllActiveVideosReturnsAnEmptyResultsetWhenNoRecordsAvailable()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize(array());

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
    
    public function testSaveWillInsertNewIfTheyDoNotAlreadyHaveAnId()
    {
        $video = new VideoModel();
        $recordData = $this->_recordData;
        unset($recordData['videoId']);
        $video->exchangeArray($recordData);

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('insert')
            ->times(1)
            ->with($recordData)
            ->andReturn(true);

        $mockTableGateway->shouldReceive('getLastInsertValue')
            ->times(1)
            ->with()
            ->andReturn(1);

        $VideoTable = new VideoTable($mockTableGateway);
        $VideoTable->save($video);
    }

    public function testSaveWillUpdateExistingIfTheyAlreadyHaveAnId()
    {
        $recordData = $this->_recordData;
        $videoId = $recordData['videoId'];
        unset($recordData['videoId']);
        $video = new VideoModel();
        $video->exchangeArray($this->_recordData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new VideoModel());
        $resultSet->initialize(array($video));

        $mockTableGateway = \Mockery::mock('Zend\Db\TableGateway\TableGateway');
        $mockTableGateway->shouldReceive('update')
            ->with($recordData, array('videoId' => $videoId))
            ->andReturn($this->_recordData);

        $VideoTable = new VideoTable($mockTableGateway);
        $VideoTable->save($video, $videoId);
    }
}
 