<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 28/07/14
 * Time: 21:09
 */

namespace VideoHosterTest\Controller;

use VideoHosterTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use VideoHoster\Controller\VideosController;
use VideoHoster\Tables\VideoTable;
use VideoHoster\Models\VideoModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Server\Method\Parameter;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Faker;

class VideosControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../config/application.config.php'
        );
        parent::setUp();

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );
    }

    public function testCanDispatchToVideoIndexPageWithoutResults()
    {
        $resultSet = new ResultSet();
        $resultSet->initialize(array());

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchActiveVideos')
            ->once()
            ->andReturn($resultSet);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos');
        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryCount(
            '//div[@class="col-lg-12"][starts-with(normalize-space(.), "Sorry, no tutorials are currently available")]/text()', 1
        );
        $this->assertXpathQueryCount('//h1[contains(text(), "All Tutorials")]', 1);
    }

    public function testCanDispatchToVideoIndexPageWithResults()
    {
        $resultSet = new ResultSet();
        $video = new VideoModel();
        $video->exchangeArray(array(
            'videoId' => 12,
            'name' => "Freddie Mercury Live",
            'slug' => "freddie-mercury-live",
            'description' => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
            'authorId' => 1,
            'statusId' => 1,
            'paymentRequirementId' => 1,
            'extract' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
            'duration' => 115,
            'publishDate' => '2008-08-01',
            'publishTime' => '11:15',
            'levelId' => 1
        ));

        $resultVideos = array($video);

        $resultSet->initialize($resultVideos);

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchActiveVideos')
            ->once()
            ->andReturn($resultSet);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos');

        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryCount('//h1[contains(text(), "All Tutorials")]', 1);

        foreach ($resultVideos as $result) {
            $this->assertXpathQueryCount("//h2[contains(text(), '{$result->name}')]", 1);
            $this->assertXpathQueryCount("//p[contains(text(), \"{$result->description}\")]", 1);
        }
    }

    /**
     * @dataProvider validVideoPagesProvider
     */
    public function testCanDispatchToValidVideoPages($validPages)
    {
        $this->dispatch('/' . $validPages);
        $this->assertResponseStatusCode(404);
    }

    public function validVideoPagesProvider()
    {
        $data = array();
        $faker = Faker\Factory::create();
        foreach(range(1, 100) as $count) {
            $data[] = array($faker->slug());
        }

        return $data;
    }
}
