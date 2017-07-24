<?php

use LarryPHP\Encryption\Crypt;

class CryptTest extends PHPUnit_Framework_TestCase
{
    public function testEncryption()
    {
        $key = str_repeat('a', 16);

        $encrypted = Crypt::encrypt('foo', $key);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', Crypt::decrypt($encrypted, $key));

        $encrypted = larryphp_encrypt('foo', $key);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', larryphp_decrypt($encrypted, $key));

        $encrypted = larryphp_encrypt_url('foo', $key);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', larryphp_decrypt_url($encrypted, $key));
    }

    public function testWithCustomCipher()
    {
        $key = str_repeat('a', 32);
        $cipher = 'AES-256-CBC';

        $encrypted = Crypt::encrypt('foo', $key, $cipher);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', Crypt::decrypt($encrypted, $key, $cipher));

        $encrypted = larryphp_encrypt('foo', $key, $cipher);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', larryphp_decrypt($encrypted, $key, $cipher));

        $encrypted = larryphp_encrypt_url('foo', $key, $cipher);
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', larryphp_decrypt_url($encrypted, $key, $cipher));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.
     */
    public function testDoNoAllowLongerKey()
    {
        Crypt::encrypt('foo', str_repeat('z', 32));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.
     */
    public function testWithBadKeyLength()
    {
        Crypt::encrypt('foo', str_repeat('a', 5));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.
     */
    public function testWithBadKeyLengthAlternativeCipher()
    {
        Crypt::encrypt('foo', str_repeat('a', 16), 'AES-256-CFB8');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.
     */
    public function testWithUnsupportedCipher()
    {
        Crypt::encrypt('foo', str_repeat('c', 16), 'AES-256-CFB8');
    }

    /**
     * @expectedException LarryPHP\Encryption\DecryptException
     * @expectedExceptionMessage The payload is invalid.
     */
    public function testExceptionThrownWhenPayloadIsInvalid()
    {
        $key = str_repeat('a', 16);
        $payload = Crypt::encrypt('foo', $key);

        $payload = str_shuffle($payload);

        Crypt::decrypt($payload, $key);
    }

    /**
     * @expectedException LarryPHP\Encryption\DecryptException
     * @expectedExceptionMessage The MAC is invalid.
     */
    public function testExceptionThrownWithDifferentKey()
    {
        $keya = str_repeat('a', 16);
        $keyb = str_repeat('b', 16);
        Crypt::decrypt(Crypt::encrypt('baz', $keya), $keyb);
    }
}
