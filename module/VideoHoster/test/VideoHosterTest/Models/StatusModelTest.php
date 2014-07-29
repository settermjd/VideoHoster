<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\StatusModel;

class StatusModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new StatusModel();
        $this->testData = array(
            'statusId' => 1,
            'name'  => "free",
        );
    }
} 