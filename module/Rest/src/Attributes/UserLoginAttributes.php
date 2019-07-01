<?php

namespace Rest\Attributes;


/**
 * Class UserLoginAttributes
 * @package Rest\Attributes
 */
class UserLoginAttributes
{

    /**
     * @var string
     */
    public $token = '';

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }



}