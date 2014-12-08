<?php
namespace VideoHosterTest\Form;

use PHPUnit_Framework_TestCase;
use Zend\Form\Form as ZendForm;
use \Zend\Form\Element\Select,
    \Zend\Form\Element\Radio;

class BasicFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Form
     */
    protected $_form;

    /**
     * @var array
     */
    protected $_formFields;

    /**
     * @var array
     */
    protected $_formProperties;

    public function setUp()
    {
        $this->_form = $this->_getForm();
        $this->_formFields = array();
        $this->_formProperties = array();
    }

    /**
     * This provides a reusable test for the availability of the Search PAC form
     *
     * @return Form
     */
    protected function _getForm()
    {
        return new ZendForm();
    }

    public function testFormInitialState()
    {
        // check the form properties
        foreach($this->_formProperties as $propertyName => $propertyValue) {
            $this->assertEquals(true,
                $this->_form->hasAttribute($propertyName),
                "Form has no property {$propertyName} element"
            );
            $this->assertEquals(
                $propertyValue,
                $this->_form->getAttribute($propertyName),
                "Form property {$propertyName} doesn't match {$propertyValue}"
            );
        }

        foreach($this->_formFields as $fieldName => $properties) {
            $this->assertEquals(true, $this->_form->has($fieldName), "Form missing element '{$fieldName}'");
            foreach($properties as $propertyName => $propertyValue) {
                switch ($propertyName) {
                    case ('type') :
                        $failMsg = sprintf(
                            "field %s should have been of type: %s",
                            $fieldName,
                            $this->_form->get($fieldName)->getAttribute('type')
                        );
                        $this->assertEquals(
                            $propertyValue,
                            $this->_form->get($fieldName)->getAttribute('type'),
                            $failMsg
                        );
                        break;

                    case ('value'):
                        $this->assertEquals(
                            $propertyValue,
                            $this->_form->get($fieldName)->getValue(),
                            "field {$fieldName} should have value: ". $this->_form->get($fieldName)->getValue()
                        );
                        break;

                    /**
                     * currently only validates a Select or Radio item
                     */
                    case ('value-options'):
                        $failMsg = sprintf(
                            "form element %s doesn't have the correct list values. It should have the following: %s",
                            $fieldName, var_export($this->_form->get($fieldName)->getValueOptions(), TRUE)
                        );
                        if ($this->_form->get($fieldName) instanceof Select ||
                            $this->_form->get($fieldName) instanceof Radio) {
                            $this->assertEquals(
                                $propertyValue,
                                $this->_form->get($fieldName)->getValueOptions(),
                                $failMsg
                            );
                        }
                        break;

                    case ('label'):
                        $failMsg = sprintf(
                            "field %s should have label matching: %s",
                            $fieldName, $this->_form->get($fieldName)->getLabel()
                        );
                        $this->assertEquals(
                            $propertyValue,
                            $this->_form->get($fieldName)->getLabel(),
                            $failMsg
                        );
                        break;

                    case ('placeholder') :
                        $this->assertEquals(
                            $propertyValue,
                            $this->_form->get($fieldName)->getAttribute('placeholder'),
                            "field {$fieldName} should placeholder matching: {$propertyValue}"
                        );
                        break;
                }
            }
        }
    }
}