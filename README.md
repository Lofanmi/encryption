LarryPHP Encryption
===================

[中文](https://github.com/Lofanmi/encryption/blob/master/README_zh-CN.md)

Thank you for choosing LarryPHP Encryption - a tiny encryptor from Laravel, for doing encryption in PHP.

[![Build Status](https://travis-ci.org/Lofanmi/encryption.svg?branch=master)](https://travis-ci.org/Lofanmi/encryption.svg)
[![Coverage Status](https://img.shields.io/codecov/c/github/Lofanmi/encryption.svg)](https://codecov.io/github/Lofanmi/encryption)
[![Releases](https://img.shields.io/github/release/Lofanmi/encryption.svg)](https://github.com/Lofanmi/encryption/releases/latest)
[![Packagist Status](https://img.shields.io/packagist/v/larryphp/encryption.svg)](https://packagist.org/packages/larryphp/encryption)

Requirements
------------

The minimum requirement is that your Web server supports PHP 5.4.

Implementation
--------------

Messages are encrypted with AES-128/256 in CBC mode and are authenticated with HMAC-SHA256 (Encrypt-then-Mac). It is implemented using the `openssl_` and `hash_hmac` functions, compatible with Laravel Encryption.

For URL application or Filenames, you can use the helper function `larryphp_encrypt_url` which makes a replacement from `['+', '/', '=']` to `['-', '_', '']`. Don't forget to use `larryphp_decrypt_url` to decrypt the payload.

See wikipedia for more infomation [Base64](https://en.wikipedia.org/wiki/Base64#RFC_3548).

>Using standard Base64 in URL requires encoding of '+', '/' and '=' characters into special percent-encoded hexadecimal sequences ('+' becomes '%2B', '/' becomes '%2F' and '=' becomes '%3D'), which makes the string unnecessarily longer.

>Another variant called modified Base64 for filename uses '-' instead of '/', because Unix and Windows filenames cannot contain '/'.

>It could be recommended to use the modified Base64 for URL instead, since then the filenames could be used in URLs also.

>For this reason, modified Base64 for URL variants exist, where the '+' and '/' characters of standard Base64 are respectively replaced by '-' and '_', so that using URL encoders/decoders is no longer necessary and have no impact on the length of the encoded value, leaving the same encoded form intact for use in relational databases, web forms, and object identifiers in general. Some variants allow or require omitting the padding '=' signs to avoid them being confused with field separators, or require that any such padding be percent-encoded. Some libraries (like org.bouncycastle.util.encoders.UrlBase64Encoder) will encode '=' to '.'.

Install
-------

To install with composer:

```sh
composer require larryphp/encryption
```

Usage
-----

```php
<?php
//------------------------------------------------------------------------------
require '/path/to/vendor/autoload.php';
//------------------------------------------------------------------------------
$key       = '0000....11112222'; // the key length is 16
$encrypted = Crypt::encrypt('foo', $key); // use AES-128-CBC default
echo Crypt::decrypt($encrypted, $key); // foo
//------------------------------------------------------------------------------
// use the helper function
echo larryphp_decrypt(
    larryphp_encrypt('foo', $key), $key
); // foo
//------------------------------------------------------------------------------
// for URL application or Filenames
echo larryphp_decrypt_url(
    larryphp_encrypt_url('foo', $key), $key
); // foo
//------------------------------------------------------------------------------
$key       = '0000....111122220000....11112222'; // the key length is 32
$cipher    = 'AES-256-CBC';
$encrypted = Crypt::encrypt('foo', $key, $cipher); // must use AES-256-CBC
echo Crypt::decrypt($encrypted, $key, $cipher); // foo
//------------------------------------------------------------------------------
// use the helper function
echo larryphp_decrypt(
    larryphp_encrypt('foo', $key, $cipher), $key, $cipher
); // foo
//------------------------------------------------------------------------------
// for URL application or Filenames
echo larryphp_decrypt_url(
    larryphp_encrypt_url('foo', $key, $cipher), $key, $cipher
); // foo
//------------------------------------------------------------------------------
// Enjoy it:)
//------------------------------------------------------------------------------
```