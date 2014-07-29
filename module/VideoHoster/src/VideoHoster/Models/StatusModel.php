<?php

namespace VideoHoster\Models;

class StatusModel 
{
    public $statusId;
    public $name;

    public function exchangeArray($data)
    {
        $this->statusId = (isset($data['statusId'])) ? $data['statusId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 