<?php

namespace VideoHoster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BusinessPagesController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function AboutAction()
    {
        return new ViewModel();
    }

    public function FaqAction()
    {
        return new ViewModel();
    }

    public function TestimonialsAction()
    {
        return new ViewModel();
    }

    /**
     * Display the impressum page, required under German law
     * 
     * @return ViewModel
     */
    public function ImpressumAction()
    {
        return new ViewModel();
    }

    public function SupportAction()
    {
        return new ViewModel();
    }

    public function PrivacyAction()
    {
        return new ViewModel();
    }

    public function CopyrightAction()
    {
        return new ViewModel();
    }

    public function DisclaimerAction()
    {
        return new ViewModel();
    }

    public function TermsAction()
    {
        return new ViewModel();
    }


}

