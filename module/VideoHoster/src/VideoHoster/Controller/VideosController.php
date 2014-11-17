<?php

namespace VideoHoster\Controller;

use VideoHoster\Tables\VideoTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class VideosController extends AbstractActionController
{
    /**
     * Set the default route to redirect to
     *
     * @var string
     */
    const DEFAULT_ROUTE = 'videos';

    /**
     * Provides connection to the videos table in the database
     *
     * @var \VideoHoster\Tables\VideoTable videoTable
     * @access protected
     */
    protected $videoTable;

    /**
     * Cache object
     *
     * @var null
     */
    protected $cache;

    /**
     *  Basic class constructor
     *
     * @param \VideoHoster\Tables\VideoTable $videoTable
     * @access public
     * @return void
     */
    public function __construct(VideoTable $videoTable, $cache = null)
    {
        $this->videoTable = $videoTable;

        if (!is_null($cache)) {
            $this->cache = $cache;
        }
    }

    public function indexAction()
    {
        return new ViewModel(
            array(
                'tutorials' => $this->videoTable->fetchActiveVideos()
            )
        );
    }

    public function ViewVideoAction()
    {
        // grab the slug from the route params
        $slug = $this->params()->fromRoute('slug');

        // Grab the video and set it in the video model, if available
        if ($video = $this->videoTable->fetchBySlug($slug)) {
            return array(
                'video' => $video
            );
        }

        // if the video can't be found, redirect to the main page
        return $this->redirect()->toRoute(
            self::DEFAULT_ROUTE,
            array('action' => 'manage')
        );
    }


}

