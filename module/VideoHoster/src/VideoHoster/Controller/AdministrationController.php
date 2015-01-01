<?php

namespace VideoHoster\Controller;

use VideoHoster\Tables\VideoTable;
use VideoHoster\Tables\StatusTable;
use VideoHoster\Tables\AuthorTable;
use VideoHoster\Tables\LevelTable;
use VideoHoster\Tables\PaymentRequirementTable;
use VideoHoster\Models\VideoModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class AdministrationController extends AbstractActionController
{
    /**
     * Set the default route to redirect to
     *
     * @var string
     */
    const DEFAULT_ROUTE = 'videos';

    const DEFAULT_PAGE = 1;

    const DEFAULT_RECORDS_PER_PAGE = 10;

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
     * @var \VideoHoster\Tables\AuthorTable authorTable
     * @access protected
     */
    protected $authorTable;

    /**
     * Provides connection to the level table in the database
     *
     * @var \VideoHoster\Tables\LevelTable levelTable
     * @access protected
     */
    protected $levelTable;

    /**
     * Provides connection to the paymentRequirement table in the database
     *
     * @var \VideoHoster\Tables\PaymentRequirementTable paymentRequirementTable
     * @access protected
     */
    protected $paymentRequirementTable;

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
        LevelTable $levelTable,
        PaymentRequirementTable $paymentRequirementTable,
        $cache = null
    ) {
        $this->videoTable = $videoTable;
        $this->statusTable = $statusTable;
        $this->authorTable = $authorTable;
        $this->levelTable = $levelTable;
        $this->paymentRequirementTable = $paymentRequirementTable;

        if (!is_null($cache)) {
            $this->cache = $cache;
        }
    }

    public function indexAction()
    {
        return array(
            'paginator' => $this->getPaginator($this->videoTable->fetchAllVideos())
        );
    }

    public function deleteAction()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('VideoHoster\Form\DeleteVideoForm');

        $view = new ViewModel();

        if ($this->getRequest()->isGet()) {
            $slug = trim($this->params()->fromRoute('slug'));
            $this->flashMessenger()->addErrorMessage('Unable to continue, no video slug provided');
            if (empty($slug)) {
                return $this->redirect()->toRoute('administration', array());
            }
            if (($video = $this->videoTable->fetchBySlug($slug)) instanceof VideoModel) {
                $form->setData($video->getArrayCopy());
                $view->setVariable('video', $video);
            } else {
                return $this->redirect()->toRoute(
                    'administration', array()
                );
            }
        }

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                if ($this->videoTable->deleteBySlug($form->get('slug')->getValue())) {
                    return $this->redirect()->toRoute(
                        'administration', array()
                    );
                } else {
                    $view->setVariable('deleteError', true);
                }
            }
        }

        $view->setVariable('form', $form);

        return $view;
    }

    public function manageAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(
                'videos', array()
            );
        }

        // grab the slug from the route params
        $slug = $this->params()->fromRoute('slug');
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('VideoHoster\Form\ManageVideoForm');
        $form->get('statusId')->setValueOptions($this->statusTable->getSelectList());
        $form->get('authorId')->setValueOptions($this->authorTable->getSelectList());
        $form->get('levelId')->setValueOptions($this->levelTable->getSelectList());
        $form->get('paymentRequirementId')->setValueOptions(
            $this->paymentRequirementTable->getSelectList()
        );

        if ($this->getRequest()->isGet()) {
            if (!empty($slug)) {
                // Grab the video and set it in the video model, if available
                if ($video = $this->videoTable->fetchBySlug($slug)) {
                    // preset the author id to the logged in user
                    $form->get('authorId')->setValue(
                        $this->zfcUserAuthentication()->getIdentity()->getId()
                    );
                    $form->setData($video->getArrayCopy());
                }
            }
        }

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                $video = new VideoModel();
                $video->exchangeArray($form->getData());
                $video->videoId = $this->videoTable->save($video);

                return $this->redirect()->toRoute(
                    'administration/manage',
                    array(
                        'slug' => trim($video->slug)
                    )
                );
            }
        }

        return array(
            'form' => $form,
        );
    }

    protected function getPaginator($resultset)
    {
        if (is_array($resultset)) {
            $paginator = new Paginator(
                new ArrayAdapter($resultset)
            );
        } else {
            $paginator = new Paginator(
                new Iterator($resultset)
            );
        }

        $paginator->setCurrentPageNumber(
            $this->params()->fromRoute(
                'page',
                self::DEFAULT_PAGE
            )
        );

        $paginator->setItemCountPerPage(
            $this->params()->fromRoute(
                'perPage',
                self::DEFAULT_RECORDS_PER_PAGE
            )
        );

        return $paginator;
    }

}

