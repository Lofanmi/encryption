<?php

use LarryPHP\Encryption\Crypt;

if (!function_exists('larryphp_encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param string $value
     * @param string $key
     * @param string $cipher
     *
     * @throws \LarryPHP\Encryption\EncryptException
     *
     * @return string
     */
    function larryphp_encrypt($value, $key, $cipher = 'AES-128-CBC')
    {
        return Crypt::encrypt($value, $key, $cipher);
    }
}

if (!function_exists('larryphp_decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $payload
     * @param string $key
     * @param string $cipher
     *
     * @throws \LarryPHP\Encryption\DecryptException
     *
     * @return string
     */
    function larryphp_decrypt($payload, $key, $cipher = 'AES-128-CBC')
    {
        return Crypt::decrypt($payload, $key, $cipher);
    }
}

if (!function_exists('larryphp_encrypt_url')) {
    /**
     * Encrypt the given value for URL parameters/filenames.
     *
     * @param string $value
     * @param string $key
     * @param string $cipher
     *
     * @throws \LarryPHP\Encryption\EncryptException
     *
     * @return string
     */
    function larryphp_encrypt_url($value, $key, $cipher = 'AES-128-CBC')
    {
        return rtrim(str_replace(['+', '/'], ['-', '_'], Crypt::encrypt($value, $key, $cipher)), '=');
    }
}

if (!function_exists('larryphp_decrypt_url')) {
    /**
     * Decrypt the given value for URL parameters/filenames.
     *
     * @param string $payload
     * @param string $key
     * @param string $cipher
     *
     * @throws \LarryPHP\Encryption\DecryptException
     *
     * @return string
     */
    function larryphp_decrypt_url($payload, $key, $cipher = 'AES-128-CBC')
    {
        return Crypt::decrypt(str_replace(['-', '_'], ['+', '/'], $payload), $key, $cipher);
    }
}
