<?php

namespace VideoHoster\Models;

class SeriesVideosModel
{
    public $seriesId;
    public $videoId;

    public function exchangeArray($data)
    {
        $this->seriesId = (isset($data['seriesId'])) ? $data['seriesId'] : null;
        $this->videoId = (isset($data['videoId'])) ? $data['videoId'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 