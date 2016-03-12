<?php

namespace LarryPHP\Encryption;

use RuntimeException;

class Crypt
{
    /**
     * Encrypt the given value.
     *
     * @param  string  $value
     * @param  string  $key
     * @param  string  $cipher
     * @return string
     *
     * @throws \LarryPHP\Encryption\EncryptException
     */
    public static function encrypt($value, $key, $cipher = 'AES-128-CBC')
    {
        if (!static::supported($key = (string) $key, $cipher)) {
            throw new RuntimeException('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');
        }
        $iv    = random_bytes(16);
        $value = openssl_encrypt(serialize($value), $cipher, $key, 0, $iv);
        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        // Once we have the encrypted value we will go ahead base64_encode the input
        // vector and create the MAC for the encrypted value so we can verify its
        // authenticity. Then, we'll JSON encode the data in a "payload" array.
        $iv   = base64_encode($iv);
        $mac  = hash_hmac('sha256', $iv . $value, $key);
        $json = json_encode(compact('iv', 'value', 'mac'));
        if (!is_string($json)) {
            throw new EncryptException('Could not encrypt the data.');
        }
        return base64_encode($json);
    }

    /**
     * Decrypt the given value.
     *
     * @param  string  $payload
     * @param  string  $key
     * @param  string  $cipher
     * @return string
     *
     * @throws \LarryPHP\Encryption\DecryptException
     */
    public static function decrypt($payload, $key, $cipher = 'AES-128-CBC')
    {
        if (!static::supported($key = (string) $key, $cipher)) {
            throw new RuntimeException('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');
        }

        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!is_array($payload) || !isset($payload['iv'], $payload['value'], $payload['mac'])) {
            throw new DecryptException('The payload is invalid.');
        }

        // Determine if the MAC for the given payload is valid.
        $bytes   = random_bytes(16);
        $calcMac = hash_hmac('sha256', hash_hmac('sha256', $payload['iv'] . $payload['value'], $key), $bytes, true);
        if (!hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calcMac)) {
            throw new DecryptException('The MAC is invalid.');
        }

        $iv        = base64_decode($payload['iv']);
        $decrypted = openssl_decrypt($payload['value'], $cipher, $key, 0, $iv);
        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }
        return unserialize($decrypted);
    }

    /**
     * Determine if the given key and cipher combination is valid.
     *
     * @param  string  $key
     * @param  string  $cipher
     * @return bool
     */
    public static function supported($key, $cipher = 'AES-128-CBC')
    {
        $length = mb_strlen($key, '8bit');
        return ($cipher === 'AES-128-CBC' && $length === 16) || ($cipher === 'AES-256-CBC' && $length === 32);
    }
}
