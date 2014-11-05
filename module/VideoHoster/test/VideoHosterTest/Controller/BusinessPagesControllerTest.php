<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Provides tests for the business pages
 *
 * These shouldn't really be that comples as the pages are just simple text.
 *
 * PHP version 5.4+
 *
 * @category   VideoHosterTest
 * @package    Controller
 * @author     Matthew Setter <matthew@maltblue.com>
 * @copyright  2014 matthew@maltblue.com
 * @since      File available since Release/Tag: 1.0
 */

namespace VideoHosterTest\Controller;

use VideoHosterTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use VideoHoster\Controller\BusinessPagesController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class BusinessPagesControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new BusinessPagesController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAboutActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'about');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testImpressumActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'impressum');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFaqActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'faq');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testTestimonialsActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'testimonials');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSupportActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'support');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPrivacyActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'privacy');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCopyrightActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'copyright');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDisclaimerActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'disclaimer');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testTermsActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'terms');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

}