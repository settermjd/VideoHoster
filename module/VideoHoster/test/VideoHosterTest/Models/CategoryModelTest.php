<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:40
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\CategoryModel;

class CategoryModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new CategoryModel();
        $this->testData = array(
            'categoryId' => 1,
            'name'  => "security",
        );
    }
} 