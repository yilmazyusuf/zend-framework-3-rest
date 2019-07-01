<?php

namespace Rest\Service;


use Zend\Crypt\BlockCipher;

/**
 * Class Encryption
 * @package Rest\Service
 */
trait Encryption
{

    /**
     * @param $secretKey
     * @param $data
     * @return string
     */
    public static function encrypt($secretKey, $data) : string
    {
        $blockCipher = self::getCipher($secretKey);
        return $blockCipher->encrypt($data);

    }

    /**
     * @param $secretKey
     * @param $data
     * @return string
     */
    public function decrypt($secretKey, $data) : string
    {
        $blockCipher = self::getCipher($secretKey);
        return $blockCipher->decrypt($data);
    }

    /**
     * @param $secretKey
     * @return BlockCipher
     */
    public static function getCipher($secretKey) : BlockCipher
    {
        $blockCipher = BlockCipher::factory('openssl', array('algo' => 'aes'));
        $blockCipher->setKey($secretKey);

        return $blockCipher;
    }

}