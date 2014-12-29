<?php
namespace VideoHosterTest\Controller;

use VideoHoster\Models\VideoModel;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\Response;
use Faker;
use Zend\Stdlib\Parameters;

class AdministrationControllerTest extends AbstractHttpControllerTestCase
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

    public function testWillRedirectFromManageVideoPageIfUserNotLoggedIn()
    {
        $this->markTestIncomplete("Currently refactoring the routing table");
        $this->dispatch('/administration/manage/test-video');
        $this->assertRedirectTo(
            '/videos',
            "Should have redirected to videos page as user isn't logged in"
        );
        $this->assertResponseStatusCode(302);
    }

    public function testCanViewManageAllVideosPageWhenLoggedIn()
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

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $result = array(
            $video
        );

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchAllVideos')
            ->once()
            ->andReturn($result);

        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $mockAuthService = \Mockery::mock('Zend\Authentication\AuthenticationService');
        $mockAuthService->shouldReceive('hasIdentity')
            ->once()
            ->andReturn(true);
        $serviceManager->setService('AuthService', $mockAuthService);

        $this->dispatch('/administration');

        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryCount(
            '//h1[contains(text(), "All Videos")]', 1, "Should display all videos header"
        );
        $this->checkVideoTableHeader();
        $this->checkVideoTableContent($result);
    }

    protected function checkVideoTableHeader()
    {
        $headers = array(
            'Slug', 'Name', 'Running Time', 'Status', 'Published (Date / Time)'
        );

        foreach ($headers as $header) {
            $this->assertXpathQueryCount(
                "//tr/th[contains(text(), '$header')]", 1, "Should have header $header"
            );
        }
    }

    protected function checkVideoTableContent($results)
    {
        foreach ($results as $result) {
            $this->assertXpathQueryCount(
                "//tr/td/a[contains(text(), '{$result->slug}')]", 1,
                "Should have slug result {$result->slug}"
            );
            $this->assertXpathQueryCount(
                "//tr/td[contains(text(), '{$result->name}')]", 1,
                "Should have name result {$result->name}"
            );
            $this->assertXpathQueryCount(
                "//tr/td[normalize-space(text())='{$result->statusId}']", 1,
                "Should have status status result {$result->statusId}"
            );
            $this->assertXpathQueryCount(
                "//tr/td[contains(text(), '{$result->duration}')]", 1,
                "Should have running time result {$result->duration}"
            );
            $this->assertXpathQueryCount(
                "//tr/td[contains(text(), '{$result->publishDate} / {$result->publishTime}')]", 1,
                "Should have publish date/time result {$result->publishDate} /
                {$result->publishTime}"
            );
        }
    }

    public function testWillLoadMatchingVideoOnManageVideoPageWhenAvailable()
    {
        $this->markTestIncomplete("Need to do some research on testing for a logged in user");

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

        $mockAuthService = \Mockery::mock('Zend\Authentication\AuthenticationService');
        $mockAuthService->shouldReceive('hasIdentity')
            ->once()
            ->andReturn(true);
        $serviceManager->setService('AuthService', $mockAuthService);
        
        $this->dispatch('/administration/manage/freddie-mercury-live');

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
        $this->dispatch('/administration/manage/test-video');

        $this->markTestIncomplete("This test has not yet been fully implemented");

        $this->assertRedirectTo('/videos/manage/test-video');
    }
}
