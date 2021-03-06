<?php

namespace CSBill\CoreBundle\Security;

class Encryption
{
    const CIPHER = MCRYPT_RIJNDAEL_256;

    const MODE = MCRYPT_MODE_ECB;

    protected $salt;

    public function __construct($salt)
    {
        $this->salt = $salt;
    }

    public function encrypt($data)
    {
        return trim(base64_encode(mcrypt_encrypt(self::CIPHER, $this->salt, $data, self::MODE, mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER, self::MODE), MCRYPT_RAND))));
    }

    public function decrypt($data)
    {
        return trim(mcrypt_decrypt(self::CIPHER, $this->salt, base64_decode($data), self::MODE, mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER, self::MODE), MCRYPT_RAND)));
    }
}
