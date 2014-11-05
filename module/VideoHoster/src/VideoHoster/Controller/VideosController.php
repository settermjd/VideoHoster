<?php

namespace VideoHoster\Controller;

use VideoHoster\Tables\VideoTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class VideosController extends AbstractActionController
{
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

    public function ByCategoryAction()
    {
        return new ViewModel();
    }

    public function BySkillLevelAction()
    {
        return new ViewModel();
    }

    public function ViewVideoAction()
    {
        return new ViewModel();
    }


}

