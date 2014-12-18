<?php

namespace VideoHosterTest\InputFilter;

use PHPUnit_Framework_TestCase;
use VideoHoster\InputFilter\VideoInputFilter;

class VideoInputFilterTest extends PHPUnit_Framework_TestCase
{
    public $inputFilter;

    public function setUp()
    {
        $this->inputFilter = new VideoInputFilter();
    }

    /** @dataProvider validatedDataProvider */
    public function testValidation($data, $valid)
    {
        $this->inputFilter->setData($data);
        $this->assertSame($valid, $this->inputFilter->isValid(), "Item did not validate properly.");
    }

    /**
     * @return array
     */
    public function validatedDataProvider()
    {
        return array(
            array(
                array(
                    'videoId' => 1,
                    'name' => 'This is a test video',
                    'slug' => 'this-is-a-test-video',
                    'authorId' => 1,
                    'statusId' => 1,
                    'paymentRequirementId' => 1,
                    'description' => 'here is a video description',
                    'extract' => 'this is an intro video',
                    'duration' => 131,
                    'publishDate' => '01-01-2014',
                    'publishTime' => '11:00',
                    'levelId' => 1,
                ),
                true
            ),
            array(
                array(
                    'videoId' => 1,
                    'name' => 'This is a test video',
                    'slug' => 'this-is-a-test-video',
                    'authorId' => 1,
                    'statusId' => 1,
                    'paymentRequirementId' => 1,
                    'description' => 'here is a video description',
                    'extract' => 'this is an intro video',
                    'duration' => 131,
                    'publishDate' => '01-01-2014',
                    'publishTime' => '11:00',
                    'levelId' => 1,
                ),
                true
            ),
        );
    }
}