<?php

namespace VideoHoster\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Filter\StripTags;
use Zend\Validator;

class VideoInputFilter extends InputFilter
{
    /**
     * Stores the names of the fields that are mandatory
     *
     * @var array
     */
    protected $_requiredFields = array(
        "authorId", "levelId", "statusId",
        "paymentRequirementId", "slug", "name", "description",
        "extract", "duration", "publishDate", "publishTime"
    );

    /**
     * Stores the names of the fields that are optional
     *
     * @var array
     */
    protected $_optionalFields = array(
        "videoId"
    );

    /**
     * Add the required fields to the input filter.
     * It's a basic utility function to avoid writing loads of redundant duplicate code.
     *
     * @return \CsuBaby\InputFilter\RecordInputFilter
     */
    protected function _addRequiredFields()
    {
        foreach ($this->_requiredFields as $fieldName) {
            $input = new Input($fieldName);
            $input->setRequired(true)
                  ->setAllowEmpty(false)
                  ->setBreakOnFailure(false)
                  ->setFilterChain($this->_getStandardFilter());

            switch ($fieldName) {

                case ("publishDate"):
                    $input->getValidatorChain()
                          ->addValidator(new Validator\Date(array(
                              "format" => "d-m-Y",
                              "locale" => "en"
                          )));
                    break;

                case ("slug"):
                    $input->getValidatorChain()
                        ->addValidator(new Validator\Regex(array(
                            "pattern" => "/[a-zA-Z][a-zA-Z-]*[a-zA-Z]/",
                        )));
                    break;

                case ("publishTime"):
                    $input->getValidatorChain()
                        ->addValidator(new Validator\Regex(array(
                            "pattern" => "/[0-9]{2}:[0-9]{2}/",
                        )));
                    break;

                case ("videoId"):
                case ("statusId"):
                case ("levelId"):
                case ("authorId"):
                case ("paymentRequirementId"):
                    $input->getValidatorChain()
                        ->addValidator(new Validator\Digits());
                    break;

            }

            $this->add($input);
        }

        return $this;
    }

    /**
     * Add the optional fields to the input filter.
     * It's a basic utility function to avoid writing loads of redundant duplicate code.
     *
     * @return \CsuBaby\InputFilter\RecordInputFilter
     */
    protected function _addOptionalFields()
    {
        foreach ($this->_optionalFields as $fieldName) {
            $input = new Input($fieldName);
            $input->setRequired(true)
                  ->setAllowEmpty(true)
                  ->setFilterChain($this->_getStandardFilter());

            switch ($fieldName) {
                case ("termType"):
                    $input->getValidatorChain()
                          ->addValidator(new Validator\InArray(array(
                                  'haystack' => array(
                                      'pre-term', 'full', 'estimated', 'pre-term, estimated', 'full, estimated', 'exact'
                                  ),
                                  'strict' => Validator\InArray::COMPARE_NOT_STRICT,
                                  'messageTemplates' => array(
                                      Validator\InArray::NOT_IN_ARRAY => "If filled, must be one of: 'pre-term', 'full', 'estimated', 'pre-term, estimated', 'full, estimated', 'exact'",
                                  )
                              )
                          ));
                    break;


            }

            $this->add($input);
        }

        return $this;
    }


    protected function _getStandardFilter()
    {
        $baseInputFilterChain = new \Zend\Filter\FilterChain();
        $baseInputFilterChain->attach(new StringTrim())
                             ->attach(new StripNewlines())
                             ->attach(new StripTags());
        return $baseInputFilterChain;
    }

    /**
     * Setup the filterchain and input fields
     */
    public function __construct()
    {
        // add the fields to the input filter
        $this->_addRequiredFields()
             ->_addOptionalFields();
    }
}