<?php

namespace VideoHoster\Models;

class VideoModel extends AbstractModel
{
    public $videoId;
    public $name;
    public $slug;
    public $authorId;
    public $statusId;
    public $paymentRequirementId;
    public $description;
    public $extract;
    public $duration;    
    public $publishDate;
    public $publishTime;
    public $levelId;

    public function exchangeArray($data)
    {
        $this->videoId = (isset($data['videoId'])) ? $data['videoId'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->slug = (isset($data['slug'])) ? $data['slug'] : null;
        $this->extract = (isset($data['extract'])) ? $data['extract'] : null;
        $this->duration = (isset($data['duration'])) ? $data['duration'] : null;
        $this->authorId = (isset($data['authorId'])) ? $data['authorId'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->statusId = (isset($data['statusId'])) ? $data['statusId'] : null;
        $this->paymentRequirementId = (isset($data['paymentRequirementId'])) ? $data['paymentRequirementId'] : null;
        $this->publishDate = (isset($data['publishDate'])) ? $data['publishDate'] : null;
        $this->publishTime = (isset($data['publishTime'])) ? $data['publishTime'] : null;
        $this->levelId = (isset($data['levelId'])) ? $data['levelId'] : null;
    }
} 