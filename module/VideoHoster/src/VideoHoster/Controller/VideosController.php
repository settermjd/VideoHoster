<?php

namespace VideoHoster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class VideosController extends AbstractActionController
{

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


}

