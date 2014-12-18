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

use Zend\Form\Element;

class ManageVideoForm extends ServerAwareForm
{
    public function __construct()
    {
        parent::__construct('ManageVideo');

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
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Video Name',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 2,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'slug',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Slug'
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'authorId',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Author',
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'statusId',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Status'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 7,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'paymentRequirementId',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Free/Paid'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 8,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'description',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Description'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'autocomplete' => false,
                'tabindex' => 8,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'extract',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Extract'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 9,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'duration',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Duration'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 11,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'publishDate',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Publish Date'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 12,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'publishTime',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Publish Time'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'tabindex' => 13,
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'levelId',
            'options' => array(
                'label_attributes' => array(
                    'class'  => 'col-sm-2 control-label'
                ),
                'label' => 'Difficulty Level'
            ),
            'attributes' => array(
                'class' => 'form-control',
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
