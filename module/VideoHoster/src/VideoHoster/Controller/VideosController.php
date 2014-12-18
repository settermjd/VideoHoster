<?php

namespace VideoHoster\Controller;

use VideoHoster\Tables\VideoTable;
use VideoHoster\Tables\StatusTable;
use VideoHoster\Tables\AuthorTable;
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
     * Provides connection to the status table in the database
     *
     * @var \VideoHoster\Tables\StatusTable statusTable
     * @access protected
     */
    protected $statusTable;

    /**
     * Provides connection to the author table in the database
     *
     * @var \VideoHoster\Tables\StatusTable authorTable
     * @access protected
     */
    protected $authorTable;

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
    public function __construct(
        VideoTable $videoTable,
        StatusTable $statusTable,
        AuthorTable $authorTable,
        $cache = null
    ) {
        $this->videoTable = $videoTable;
        $this->statusTable = $statusTable;
        $this->authorTable = $authorTable;

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

    public function manageAction()
    {
        // grab the slug from the route params
        $slug = $this->params()->fromRoute('slug');

        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('VideoHoster\Form\ManageVideoForm');

        if (!empty($slug)) {
            // Grab the video and set it in the video model, if available
            if ($video = $this->videoTable->fetchBySlug($slug)) {
                $form->setData($video->getArrayCopy());
                $form->get('statusId')->setValueOptions(
                    $this->statusTable->getSelectList()
                );
                $form->get('authorId')->setValueOptions(
                    $this->authorTable->getSelectList()
                );
            }
        }

        return array(
            'form' => $form,
            'formElements' => array(
                'videoId',
                'name',
                'slug',
                'authorId',
                'statusId',
                'paymentRequirementId',
                'description',
                'extract',
                'duration',
                'publishDate',
                'publishTime',
                'levelId'
            )
        );
    }

}

