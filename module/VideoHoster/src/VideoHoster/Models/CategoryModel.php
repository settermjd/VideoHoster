<?php

namespace VideoHoster\Models;

class CategoryModel 
{
    public $categoryId;
    public $name;

    public function exchangeArray($data)
    {
        $this->categoryId = (isset($data['categoryId'])) ? $data['categoryId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 