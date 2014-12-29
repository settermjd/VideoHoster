<?php
namespace VideoHosterTest\Controller;

use VideoHoster\Models\VideoModel;
use Zend\Db\Adapter\Driver\Sqlsrv\Result;
use Zend\Db\ResultSet\ResultSet;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\Response;
use Faker;
use Zend\Stdlib\Parameters;

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

        $statusTable = \Mockery::mock('VideoHoster\Tables\StatusTable');
        $statusTable->shouldReceive('getSelectList')
            ->once()
            ->andReturn(array(
                '1' => 'draft',
                '2' => 'live'
            ));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\StatusTable', $statusTable
        );

        $authorTable = \Mockery::mock('VideoHoster\Tables\AuthorTable');
        $authorTable->shouldReceive('getSelectList')
            ->once()
            ->andReturn(array(
                '1' => 'Matthew Setter',
            ));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\AuthorTable', $authorTable
        );

        $levelTable = \Mockery::mock('VideoHoster\Tables\LevelTable');
        $levelTable->shouldReceive('getSelectList')
            ->once()
            ->andReturn(array(
                '1' => 'Matthew Setter',
            ));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\LevelTable', $levelTable
        );

        $paymentRequirementTable = \Mockery::mock('VideoHoster\Tables\PaymentRequirementTable');
        $paymentRequirementTable->shouldReceive('getSelectList')
            ->once()
            ->andReturn(array(
                '1' => 'Free',
            ));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\PaymentRequirementTable', $paymentRequirementTable
        );
    }

    protected function checkPageHeader()
    {
        $this->assertXpathQueryCount('//a[@class="navbar-brand"][contains(text(), "ZFCasts")]', 1);
        $this->assertXpathQueryCount('//a[contains(text(), "FAQ")]', 1);
        $this->assertXpathQueryCount('//a[contains(text(), "Impressum")]', 1);
        $this->assertXpathQueryCount('//a[contains(text(), "Disclaimer")]', 1);
        $this->assertXpathQueryCount('//a[contains(text(), "Privacy")]', 1);
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
        $this->checkPageHeader();
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
        $this->checkPageHeader();

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
        $this->checkPageHeader();
    }

    public function testCanDispatchToFreeVideoPages()
    {
        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchFreeVideos')
            ->once()
            ->andReturn(new ResultSet());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos/free');
        $this->assertResponseStatusCode(200);
        $this->checkPageHeader();
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

    public function testCanDispatchToViewVideoPages()
    {
        $video = new VideoModel();
        $slug = 'freddie-mercury-live';
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

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->once()
            ->andReturn($video);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos/' . $slug);
        $this->assertResponseStatusCode(200);
        $this->checkPageHeader();
    }

    public function testRedirectToIndexPageIfVideoNotAvailableOnViewPage()
    {
        $video = new VideoModel();
        $slug = 'freddie-mercury-live';

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->once()
            ->andReturn(null);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos/' . $slug);
        $this->assertRedirect();
        $this->assertActionName('ViewVideo');
        $this->assertControllerClass('VideosController');
        $this->assertMatchedRouteName('videos/view-video');
    }

    public function testCanViewVideoIfAvailable()
    {
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
        $slug = 'freddie-mercury-live';

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->once()
            ->andReturn($video);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/videos/' . $slug);
        $this->assertActionName('ViewVideo');
        $this->assertControllerClass('VideosController');
        $this->assertMatchedRouteName('videos/view-video');
        $this->checkPageHeader();
    }

    public function testWillLoadMatchingVideoOnManageVideoPageWhenAvailable()
    {
        $video = new VideoModel();
        $video->exchangeArray(array(
            'videoId' => 12,
            'name' => "Freddie Mercury Live",
            'slug' => "freddie-mercury-live",
            'description' => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using.",
            'authorId' => 1,
            'statusId' => 1,
            'paymentRequirementId' => 1,
            'extract' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
            'duration' => 115,
            'publishDate' => '2008-08-01',
            'publishTime' => '11:15',
            'levelId' => 1
        ));
        $slug = 'freddie-mercury-live';

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->once()
            ->andReturn($video);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );
        
        $this->dispatch('/videos/manage/freddie-mercury-live');

        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="name"][contains(@value, "Freddie Mercury Live")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="hidden"][@name="videoId"][contains(@value, "12")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="slug"][contains(@value, "freddie-mercury-live")]', 1
        );
        $this->assertXpathQueryCount(
            '//textarea[@name="description"][contains(text(), "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using.")]', 1
        );
        /*$this->assertXpathQueryCount(
            '//input[@type="select"][@name="authorId"][contains(@value, "1")]', 1
        );
        $this->assertXpathQueryCount(
            '//select[@name="statusId"][contains(text(), "1")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="select"][@name="paymentRequirementId"][contains(@value, "1")]', 1
        );*/
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="extract"][contains(@value, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="duration"][contains(@value, "115")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="publishDate"][contains(@value, "2008-08-01")]', 1
        );
        $this->assertXpathQueryCount(
            '//input[@type="text"][@name="publishTime"][contains(@value, "11:15")]', 1
        );
        /*$this->assertXpathQueryCount(
            '//input[@type="select"][@name="levelId"][contains(@value, "1")]', 1
        );*/
    }

    public function testWillRedirectToManageRecordAfterSuccessfulUpdate()
    {
        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters(
                array(
                    'videoId' => 1,
                    'name' => 'test video',
                    'slug' => 'test-video',
                    'authorId' => 1,
                    'statusId' => 1,
                    'paymentRequirementId' => 1,
                    'description' => "here is a description",
                    'extract' => 'here is a',
                    'duration' => 131,
                    'publishDate' => '2015-01-01',
                    'publishTime' => '11:00',
                    'levelId' => 1
                )
            ));
        $this->dispatch('/');

        $this->markTestIncomplete("This test has not yet been fully implemented");

        $this->assertRedirectTo('/videos/manage/test-video');
    }
}
