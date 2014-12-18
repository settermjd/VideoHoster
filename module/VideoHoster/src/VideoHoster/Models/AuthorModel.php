<?php

namespace VideoHoster\Models;

class AuthorModel
{
    public $userId;
    public $username;
    public $email;
    public $displayName;
    public $password;
    public $state;

    public function exchangeArray($data)
    {
        $this->userId = (isset($data['userId'])) ? $data['userId'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->displayName = (isset($data['displayName'])) ? $data['displayName'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->state = (isset($data['state'])) ? $data['state'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
} 