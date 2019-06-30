<?php

namespace Rest\Service;


use Zend\Crypt\BlockCipher;

trait Encryption
{

    public static function encrypt($secretKey, $data) : string
    {
        $blockCipher = self::getCipher($secretKey);
        return $blockCipher->encrypt($data);

    }

    public function decrypt($secretKey, $data) : string
    {
        $blockCipher = self::getCipher($secretKey);
        return $blockCipher->decrypt($data);
    }

    public static function getCipher($secretKey) : BlockCipher
    {
        $blockCipher = BlockCipher::factory('openssl', array('algo' => 'aes'));
        $blockCipher->setKey($secretKey);

        return $blockCipher;
    }

}