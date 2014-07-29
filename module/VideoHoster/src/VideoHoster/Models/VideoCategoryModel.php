<?php

namespace VideoHoster\Models;

class VideoCategoryModel 
{
    public $categoryId;
    public $videoId;

    public function exchangeArray($data)
    {
        $this->categoryId = (isset($data['categoryId'])) ? $data['categoryId'] : null;
        $this->videoId = (isset($data['videoId'])) ? $data['videoId'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 