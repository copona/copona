<?php
final class Encryption {
    /* IMPORTANT NOTE:

      This encryption class has just been implemented in order to remove the use of PHP's mycrypt() function,
      which has been deprecated, without breaking anything that depends on it.

      Key storage remains an issue (the key is still stored in the Opencart database). It would be best to simply
      NOT utilize this class at all, as there doesn't seem to be any benefit as currently implemented in Opencart.

      --------------
      How it works:
      --------------
      encrypt() function returns a text string composed of cipher method, salt, and encrypted data.

      decrypt() function takes the output of encrypt() and reverses it.

     */
    private $key;
    private $iv;
    private $cipher;

    public function __construct($key) {
        $this->key = $key;
    }

    public function encrypt($value) {
        $this->cipher = 'aes-256-ctr';                      // supported ciphers can be listed by using calling 'openssl_get_cipher_methods()'
        $this->iv = openssl_random_pseudo_bytes(16);    // this value might vary depending on the cipher chosen

        return $this->cipher . '.' . bin2hex($this->iv) . '.' . bin2hex(openssl_encrypt($value, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv));
    }

    public function decrypt($value) {
        $data = explode('.', $value);
        $this->cipher = $data[0];
        $this->iv = $data[1];
        $ciphertext = $data[2];

        return openssl_decrypt(hex2bin($ciphertext), $this->cipher, $this->key, OPENSSL_RAW_DATA, hex2bin($this->iv));
    }

}