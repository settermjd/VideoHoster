<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\VideoCategoryModel;

class VideoCategoryModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new VideoCategoryModel();
        $this->testData = array(
            'videoId' => 1,
            'categoryId' => 1,
        );
    }
} 