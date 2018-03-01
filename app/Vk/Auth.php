<?php

namespace App\Vk;


class Auth
{
    public $authToken;

    public function __construct($token)
    {
        $this->authToken = $token;
    }
}