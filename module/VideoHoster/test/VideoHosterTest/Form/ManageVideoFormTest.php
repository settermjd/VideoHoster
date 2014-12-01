<?php
namespace VideoHosterTest\Form;

use VideoHoster\Form\ManageVideoForm;

class ManageVideoFormTest extends BasicFormTest
{
    /**
     * @var ManageVideoForm
     *
     */
    protected $_form;

    public function setUp()
    {
        $this->_form = $this->_getForm();

        $this->_formFields = array(
            'videoId' => array(
                'type' => 'hidden'
            ),
            'name' => array(
                'type' => 'text',
                'label' => 'Video Name'
            ),
            'slug' => array(
                'type' => 'text',
                'label' => 'Slug'
            ),
            'authorId' => array(
                'type' => 'hidden',
            ),
            'statusId' => array(
                'type' => 'select',
                'label' => 'Status'
            ),
            'paymentRequirementId' => array(
                'type' => 'select',
                'label' => 'Free/Paid'
            ),
            'description' => array(
                'type' => 'textarea',
                'label' => 'Description'
            ),
            'extract' => array(
                'type' => 'textarea',
                'label' => 'Extract'
            ),
            'duration' => array(
                'type' => 'text',
                'label' => 'Duration'
            ),
            'publishDate' => array(
                'type' => 'text',
                'label' => 'Publish Date'
            ),
            'publishTime' => array(
                'type' => 'text',
                'label' => 'Publish Time'
            ),
            'levelId' => array(
                'type' => 'select',
                'label' => 'Difficulty Level'
            )
        );

        $this->_formProperties = array(
            'name' => 'ManageVideo'
        );
    }

    /**
     * This provides a reusable test for the availability of the Search PAC form
     *
     * @return ManageVideoForm
     */
    protected function _getForm()
    {
        return new ManageVideoForm();
    }

    public function testFormInitialState()
    {
        parent::testFormInitialState();

        $this->assertEquals("Save",
            $this->_form->get('submit')->getLabel(),
            "Submit button's label is not correctly set"
        );

    }
}