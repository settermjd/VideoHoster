<?php

namespace VideoHosterTest\Models;

use VideoHoster\Models\AuthorModel;

class AuthorModelTest extends ModelTestAbstract
{
    public function setUp()
    {
        $this->model = new AuthorModel();
        // Need to revisit the way this is handled
        /*$this->testData = array(
            'userId' => 1,
            'username'  => "settermjd",
            'email' => "matthew@maltblue.com",
            'displayName'  => "Matthew Setter",
            'password' => "password",
            'state'  => "1",
        );*/
        $this->testData = array();
    }
} 