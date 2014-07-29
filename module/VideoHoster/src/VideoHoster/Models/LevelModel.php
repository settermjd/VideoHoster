<?php

namespace VideoHoster\Models;

class LevelModel 
{
    public $levelId;
    public $name;

    public function exchangeArray($data)
    {
        $this->levelId = (isset($data['levelId'])) ? $data['levelId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 