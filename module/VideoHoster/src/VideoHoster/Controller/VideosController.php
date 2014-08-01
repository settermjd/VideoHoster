<?php

namespace VideoHoster\Controller;

use VideoHoster\Tables\VideoTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class VideosController extends AbstractActionController
{
    protected $videoTable;

    public function __construct(VideoTable $videoTable)
    {
        $this->videoTable = $videoTable;
    }

    public function indexAction()
    {
        return new ViewModel();
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

