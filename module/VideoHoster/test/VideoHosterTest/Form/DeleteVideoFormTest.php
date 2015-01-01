<?php
namespace VideoHosterTest\Form;

use VideoHoster\Form\DeleteVideoForm;

class DeleteVideoFormTest extends BasicFormTest
{
    /**
     * @var ManageVideoForm
     *
     */
    protected $_form;

    public function setUp()
    {
        $this->_form = $this->_getForm();

        $this->_getForm()->init();

        $this->_formFields = array(
            'slug' => array(
                'type' => 'hidden'
            ),
            'submit' => array(
                'type' => 'submit',
                'class' => 'btn btn-danger'
            ),
        );

        $this->_formProperties = array(
            'name' => 'DeleteVideo'
        );
    }

    /**
     * This provides a reusable test for the availability of the Search PAC form
     *
     * @return ManageVideoForm
     */
    protected function _getForm()
    {
        return new DeleteVideoForm();
    }

    public function testFormInitialState()
    {
        parent::testFormInitialState();

        $this->assertEquals("Delete Video",
            $this->_form->get('submit')->getLabel(),
            "Submit button's label is not correctly set"
        );

    }
}
