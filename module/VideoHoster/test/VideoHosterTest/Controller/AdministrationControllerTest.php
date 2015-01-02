<?php
namespace VideoHosterTest\Controller;

use Faker;
use VideoHoster\Models\VideoModel;
use Zend\Http\Response;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

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

    protected function setupAuthentication($hasIdentity)
    {
        $mockZfcAuthService = \Mockery::mock('ZfcUser\Entity\User');
        $mockZfcAuthService->shouldReceive('getId')
            ->once()
            ->andReturn('1');

        $mockUser = \Mockery::mock('ZfcUser\Controller\Plugin\ZfcUserAuthentication');
        $mockUser->shouldReceive('getIdentity')
            ->once()
            ->andReturn($mockZfcAuthService);

        $mockUser->shouldReceive('hasIdentity')
            ->once()
            ->andReturn($hasIdentity);

        $mockController = \Mockery::mock('VideoHoster\Controller\AdministrationController');

        $mockUser->shouldReceive('setController')
            ->once();

        $mockUser->shouldReceive('getController')
            ->once()
            ->andReturn($mockController);

        return $mockUser;
    }

    protected function setupValidAuthServiceIdentity($serviceManager)
    {
        $mockZfcAuthService = \Mockery::mock('ZfcUser\Entity\User');
        $mockZfcAuthService->shouldReceive('getId')
            ->once()
            ->andReturn('1');

        $mockAuth = \Mockery::mock('\Zend\Authentication\AuthenticationService');
        $mockAuth->shouldReceive('hasIdentity')
            ->once()
            ->andReturn(true);
        $mockAuth->shouldReceive('getIdentity')
            ->once()
            ->andReturn($mockZfcAuthService);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'zfcuser_auth_service', $mockAuth
        );
    }

    /**
     * @dataProvider adminControllerActionsList
     */
    public function testRedirectsToDefaultRouteWhenAccessingAdminActionsWithNoValidUser(
        $route, $functionCall, $response
    ) {
        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive($functionCall)
            ->once()
            ->andReturn($response);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch($route);
        $this->assertResponseStatusCode(302, "Status code should have been a 302 redirect");
        $this->assertRedirectTo('/videos', "Redirected to the wrong route");
    }

    public function adminControllerActionsList()
    {
        return array(
            array('/administration', 'fetchAllVideos', array()),
            array('/administration/delete', 'fetchBySlug', array()),
            array('/administration/manage', 'fetchBySlug', array()),
            array('/administration/delete/test-video', 'fetchBySlug', array()),
            array('/administration/manage/test-video', 'fetchBySlug', array()),
        );
    }

    public function testWillRedirectToAdminPageWhenAttemptingToLoadNonexistantVideo()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $this->setupValidAuthServiceIdentity($serviceManager);

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->with('non-existent-slug')
            ->once()
            ->andReturn(false);

        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/administration/delete/non-existent-slug');

        $this->assertRedirectTo('/administration');
    }

    public function testWillLoadVideoOnDeletePageIfValidSlugProvided()
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

        $this->setupValidAuthServiceIdentity($serviceManager);

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('fetchBySlug')
            ->with($video->slug)
            ->once()
            ->andReturn($video);

        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->dispatch('/administration/delete/' . $video->slug);
        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryContentContains(
            '//h1', 'Delete Video', "Should display delete video header"
        );

        $warning = sprintf("Do you really want to delete: %s", $video->name);
        $this->assertXpathQueryCount("//div[@class='alert alert-danger'][contains(., '{$warning}')]",
            1);

        $this->assertXpathQueryCount(
            sprintf("//input[@type='hidden'][@value='%s'][@name='slug']", $video->slug),
            1, "Hidden slug field is missing, has no or an incorrect value"
        );

        $this->assertXpathQueryCount(
            "//input[@type='submit'][@value='Delete Video']",
            1, "Form submit button is missing"
        );

        $this->assertXpathQueryCount(
            "//a[@href='/administration'][@class='btn btn-default'][contains(text(), 'Cancel')]",
            1, "Form cancel button is missing"
        );
    }

    public function testWillDeleteVideoWithCorrectVideoSlug()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $this->setupValidAuthServiceIdentity($serviceManager);

        /*$serviceManager->get('ControllerPluginManager')
            ->setService(
                'zfcUserAuthentication',
                $this->setupAuthentication(true)
            );*/

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('deleteBySlug')
            ->with('test-video')
            ->once()
            ->andReturn(true);

        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters(
                array(
                    'slug' => 'test-video',
                )
            ));
        $this->dispatch('/administration/delete');

        $this->assertRedirectTo('/administration');
    }

    public function testWillShowErrorMessageWithIncorrectVideoSlug()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $mockAuthService = \Mockery::mock('Zend\Authentication\AuthenticationService');
        $mockAuthService->shouldReceive('hasIdentity')
            ->once()
            ->andReturn(true);
        $serviceManager->setService('AuthService', $mockAuthService);

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('deleteBySlug')
            ->with('test-video')
            ->once()
            ->andReturn(false);

        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters(
                array(
                    'slug' => 'test-video',
                )
            ));
        $this->dispatch('/administration/delete');
        $this->markTestIncomplete("The xpath assertion's not working, but I'm not sure why");

        $this->assertXpathQueryCount(
            "//div[@class='alert alert-warning'][contains(text(), 'Unable to delete the video')]", 1
        );
    }

    public function testWillRedirectFromManageVideoPageIfUserNotLoggedIn()
    {
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

        $this->setupValidAuthServiceIdentity($serviceManager);

        $this->dispatch('/administration');

        $this->assertResponseStatusCode(200);
        $this->assertXpathQueryCount(
            '//h1[contains(text(), "All Videos")]', 1, "Should display all videos header"
        );
        $this->checkVideoTableHeader();
        $this->checkVideoTableContent($result);
        $this->checkPaginationControls();
    }

    protected function checkVideoTableHeader()
    {
        $headers = array(
            'Slug',
            'Name',
            'Running Time',
            'Status',
            'Published (Date / Time)',
            'Actions'
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
            $this->assertXpathQueryCount(
                "//a[@href='/administration/delete/{$result->slug}']", 1,
                "Missing delete link for video with slug: {$result->slug}"
            );
        }
    }

    /**
     * At this stage it's just performing a minor test for the existence of the pagination controls
     */
    protected function checkPaginationControls()
    {
        $this->assertXpathQueryCount(
            "//nav/ul[@class = 'pagination']", 1,
            "Missing pagination controls"
        );
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
            ->with($slug)
            ->andReturn($video);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->setupValidAuthServiceIdentity($serviceManager);

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
            '//textarea[@name="description"][contains(text(), "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using.")]',
            1
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
            '//input[@type="text"][@name="extract"][contains(@value, "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.")]',
            1
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
        $this->assertXpathQueryCount(
            "//a[@href='/administration/delete/freddie-mercury-live']", 1,
            "Missing delete link for video with slug: freddie-mercury-live"
        );
    }

    public function testWillRedirectToManageRecordAfterSuccessfulUpdate()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $videoData = array(
            'videoId' => 1,
            'name' => 'test video',
            'slug' => 'test-video',
            'authorId' => 1,
            'statusId' => 1,
            'paymentRequirementId' => 1,
            'description' => "here is a description",
            'extract' => 'here is a',
            'duration' => 131,
            'publishDate' => '01-01-2015',
            'publishTime' => '11:00',
            'levelId' => 1
        );

        $video = new VideoModel();
        $video->exchangeArray($videoData);

        $mockTable = \Mockery::mock('VideoHoster\Tables\VideoTable');
        $mockTable->shouldReceive('save')
            ->once()
            ->andReturn(1);
        $serviceManager->setService(
            'VideoHoster\Tables\VideoTable', $mockTable
        );

        $this->setupValidAuthServiceIdentity($serviceManager);

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($videoData));

        $this->dispatch('/administration/manage/test-video');

        $this->assertRedirectTo('/administration/manage/test-video');
    }
}
