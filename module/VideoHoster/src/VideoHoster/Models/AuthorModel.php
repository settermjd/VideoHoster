<?php

namespace VideoHoster\Models;

class AuthorModel extends AbstractModel
{
    public $userId;
    public $username;
    public $email;
    public $displayName;
    public $password;
    public $state;

    public function exchangeArray($data)
    {
        $this->userId = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->displayName = (isset($data['display_name'])) ? $data['display_name'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->state = (isset($data['state'])) ? $data['state'] : null;
    }
} 