<?php

namespace VideoHoster\Models;

class SeriesModel extends AbstractModel
{
    public $seriesId;
    public $name;
    public $description;

    public function exchangeArray($data)
    {
        $this->seriesId = (isset($data['seriesId'])) ? $data['seriesId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }
} 