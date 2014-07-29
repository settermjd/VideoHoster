<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\SeriesModel;

class SeriesModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new SeriesModel();
        $this->testData = array(
            'seriesId' => 1,
            'name'  => "free",
            'description'  => "free",
        );
    }
} 