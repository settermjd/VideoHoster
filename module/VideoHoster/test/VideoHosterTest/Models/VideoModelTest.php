<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/07/14
 * Time: 17:25
 */

namespace VideoHosterTest\Models;

use VideoHoster\Models\VideoModel;

class VideoModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new VideoModel();
        $this->testData = array(
            'videoId' => 1,
            'name'  => "zend framework security",
            'authorId'  => "1 Queen St",
            'statusId'  => "",
            'description'  => "Brisbane",
            'extract'  => "Qld",
            'duration'  => "4000",
            'publishDate'  => 61,
            'publishTime'  => 61,
            'levelId'  => 61,
            'paymentRequirementId' => 1,
            'slug'  => "zend-framework-security",
        );
    }
} 