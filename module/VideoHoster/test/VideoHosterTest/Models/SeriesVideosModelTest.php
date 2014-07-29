<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\SeriesVideosModel;

class SeriesVideosModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new SeriesVideosModel();
        $this->testData = array(
            'seriesId' => 1,
            'videoId'  => 1,
        );
    }
} 