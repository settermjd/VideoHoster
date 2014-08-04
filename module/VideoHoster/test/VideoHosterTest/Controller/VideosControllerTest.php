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
        );
    }

    /**
     * @dataProvider validCategoryProvider
     */
    public function testCanDispatchToValidCategoryPages($validCategory)
    {
        $this->dispatch('/tutorials/category/' . $validCategory);
        $this->assertResponseStatusCode(200);
    }

    public function validCategoryProvider()
    {
        $data = array();
        $faker = Faker\Factory::create();
        foreach(range(1, 100) as $count) {
            $data[] = array($faker->slug());
        }

        return $data;
    }

    /**
     * @dataProvider invalidCategoryProvider
     */
    public function testCannotDispatchToInvalidCategoryPages($validCategory)
    {
        $this->dispatch('/tutorials/category/' . $validCategory);
        $this->assertResponseStatusCode(404);
    }

    public function invalidCategoryProvider()
    {
        return array(
            array('security01'),
            array('service+manager'),
            array('21forms'),
            array('module-manager£@!'),
            array('input_filter.,/?><')
        );
    }

    /**
     * @dataProvider validSkillLevelProvider
     */
    public function testCanDispatchToValidSkillLevelPages($validSkill)
    {
        $this->dispatch('/tutorials/skill/' . $validSkill);
        $this->assertResponseStatusCode(200);
    }

    public function validSkillLevelProvider()
    {
        return array(
            array('beginner'),
            array('intermediate'),
            array('advanced'),
        );
    }

    /**
     * @dataProvider invalidSkillLevelProvider
     */
    public function testCannotDispatchToInvalidSkillLevelPages($validSkill)
    {
        $this->dispatch('/tutorials/skill/' . $validSkill);
        $this->assertResponseStatusCode(404);
    }

    public function invalidSkillLevelProvider()
    {
        return array(
            array('starting'),
            array('legend'),
            array('l33th@x0r'),
        );
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