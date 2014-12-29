<?php

namespace VideoHoster\Models;

class SeriesVideosModel extends AbstractModel
{
    public $seriesId;
    public $videoId;

    public function exchangeArray($data)
    {
        $this->seriesId = (isset($data['seriesId'])) ? $data['seriesId'] : null;
        $this->videoId = (isset($data['videoId'])) ? $data['videoId'] : null;
    }
} 