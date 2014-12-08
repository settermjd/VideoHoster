<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * A form which allows admin staff to manage the records in the system
 *
 * PHP version 5.4
 *
 * @category   VideoHoster
 * @package    Form
 * @author     Matthew Setter <matthew@maltblue.com>
 * @copyright  2014 Matthew Setter, http://www.matthewsetter.com
 * @version    SVN: $Id$
 */
namespace VideoHoster\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ManageVideoForm extends ServerAwareForm
{
    public function __construct()
    {
        parent::__construct('ManageVideo');

        /*$this->setAttribute('method', 'post')
            ->setAttribute('action', '/video/manage')
            ->setAttribute('class', 'form-horizontal');*/

        // Add form elements
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'videoId',
            'options' => array(),
            'attributes' => array()
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Video Name',
            ),
            'attributes' => array(
                'class' => 'input-medium',
                'tabindex' => 2,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'slug',
            'options' => array(
                'label' => 'Slug'
            ),
            'attributes' => array(
                'class' => 'input-medium',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'authorId',
            'options' => array(
                'label' => 'Author',
            ),
            'attributes' => array()
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'statusId',
            'options' => array(
                'label' => 'Status'
            ),
            'attributes' => array(
                'tabindex' => 7,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'paymentRequirementId',
            'options' => array(
                'label' => 'Free/Paid'
            ),
            'attributes' => array(
                'tabindex' => 8,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'options' => array(
                'label' => 'Description'
            ),
            'attributes' => array(
                'autocomplete' => false,
                'tabindex' => 8,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'extract',
            'options' => array(
                'label' => 'Extract'
            ),
            'attributes' => array(
                'tabindex' => 9,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'duration',
            'options' => array(
                'label' => 'Duration'
            ),
            'attributes' => array(
                'tabindex' => 11,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'publishDate',
            'options' => array(
                'label' => 'Publish Date'
            ),
            'attributes' => array(
                'class' => 'input-large',
                'tabindex' => 12,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'publishTime',
            'options' => array(
                'label' => 'Publish Time'
            ),
            'attributes' => array(
                'class' => 'input-large',
                'tabindex' => 13,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'levelId',
            'options' => array(
                'label' => 'Difficulty Level'
            ),
            'attributes' => array(
                'class' => 'input-large',
                'tabindex' => 12,
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Save'
            ),
            'attributes' => array(
                'class' => 'btn btn-primary',
                'tabindex' => 19,
            )
        ));

        $this->get('submit')->setValue('Save');

    }
}
