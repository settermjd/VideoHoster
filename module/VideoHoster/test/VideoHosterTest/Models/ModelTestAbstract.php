<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 5.4
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Matthew Setter <matthew@maltblue.com>
 * @copyright  2014 Client/Author
 * @see        Enter if required
 * @since      File available since Release/Tag:
 */

namespace VideoHosterTest\Models;

use PHPUnit_Framework_TestCase;

abstract class ModelTestAbstract extends PHPUnit_Framework_TestCase
{
    protected $model;
    protected $testData;

    public function testInitialState()
    {
        foreach (array_keys($this->testData) as $property) {
            $this->assertNull($this->model->$property, "$property should initially be null");
        }
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $this->model->exchangeArray($this->testData);

        foreach ($this->testData as $property => $value) {
            $this->assertSame(
                $value,
                $this->model->$property,
                "$property was not set correctly"
            );
        }
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $this->model->exchangeArray(array());

        foreach (array_keys($this->testData) as $property) {
            $this->assertNull($this->model->$property, "$property should have defaulted to null");
        }
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $this->model->exchangeArray($this->testData);
        $copyArray = $this->model->getArrayCopy();

        foreach ($this->testData as $property => $value) {
            $this->assertSame($value, $copyArray[$property], "$property was not set correctly");
        }
    }
}