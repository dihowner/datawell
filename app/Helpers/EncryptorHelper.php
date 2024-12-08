<?php
namespace App\Helpers;

class EncryptorHelper {
    
    protected $encryptKey, $initVector;

    public function __construct()
    {
        $this->encryptKey = config('app.encryption_key');
        $this->initVector = config('app.init_vector');
    }

    public function encryptCredential($data)  {
        return base64_encode(openssl_encrypt($data, "AES-256-CBC", $this->encryptKey, 0, $this->initVector));
    }

    public function decryptCredential($data) {
        return openssl_decrypt(base64_decode($data), "AES-256-CBC", $this->encryptKey, 0, $this->initVector);
    }
}
?>