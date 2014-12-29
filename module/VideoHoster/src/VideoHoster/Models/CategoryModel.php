<?php

namespace VideoHoster\Models;

class CategoryModel extends AbstractModel
{
    public $categoryId;
    public $name;

    public function exchangeArray($data)
    {
        $this->categoryId = (isset($data['categoryId'])) ? $data['categoryId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }
} 