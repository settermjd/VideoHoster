<?php

namespace VideoHoster\Models;

class LevelModel extends AbstractModel
{
    public $levelId;
    public $name;

    public function exchangeArray($data)
    {
        $this->levelId = (isset($data['levelId'])) ? $data['levelId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }
} 