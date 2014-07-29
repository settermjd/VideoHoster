<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\LevelModel;

class LevelModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new LevelModel();
        $this->testData = array(
            'levelId' => 1,
            'name'  => "basic",
        );
    }
} 