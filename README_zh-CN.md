LarryPHP 加密库
===================

感谢您选择LarryPHP加密库，它从Laravel核心分离，快速小巧，跟Laravel完全兼容。

[![StyleCI](https://styleci.io/repos/53662831/shield?style=flat&branch=master)](https://styleci.io/repos/53662831)
[![Build Status](https://travis-ci.org/Lofanmi/encryption.svg?branch=master)](https://travis-ci.org/Lofanmi/encryption)
[![codecov.io](http://codecov.io/github/Lofanmi/encryption/coverage.svg?branch=master)](http://codecov.io/github/github/Lofanmi/encryption?branch=master)
[![Total Downloads](https://poser.pugx.org/larryphp/encryption/downloads)](https://packagist.org/packages/larryphp/encryption)
[![Latest Stable Version](https://poser.pugx.org/larryphp/encryption/v/stable)](https://packagist.org/packages/larryphp/encryption)
[![Latest Unstable Version](https://poser.pugx.org/larryphp/encryption/v/unstable)](https://packagist.org/packages/larryphp/encryption)
[![License](https://poser.pugx.org/larryphp/encryption/license)](https://packagist.org/packages/larryphp/encryption)

依赖
------------

PHP5.4+，兼容PHP7。

安装
-------

使用composer，在终端输入如下命令：

```sh
composer require larryphp/encryption
```

**请学会使用简单方便的composer！**

用法
-----

```php
<?php
//------------------------------------------------------------------------------
require '/path/to/vendor/autoload.php';
//------------------------------------------------------------------------------
$key       = '0000....11112222'; // 密钥长度：16
$encrypted = Crypt::encrypt('foo', $key); // 默认使用AES-128-CBC
echo Crypt::decrypt($encrypted, $key); // foo
//------------------------------------------------------------------------------
// 使用助手函数
echo larryphp_decrypt(
    larryphp_encrypt('foo', $key), $key
); // foo
//------------------------------------------------------------------------------
// URL或文件名
echo larryphp_decrypt_url(
    larryphp_encrypt_url('foo', $key), $key
); // foo
//------------------------------------------------------------------------------
$key       = '0000....111122220000....11112222'; // 密钥长度：32
$cipher    = 'AES-256-CBC';
$encrypted = Crypt::encrypt('foo', $key, $cipher); // 必须使用AES-256-CBC
echo Crypt::decrypt($encrypted, $key, $cipher); // foo
//------------------------------------------------------------------------------
// 使用助手函数
echo larryphp_decrypt(
    larryphp_encrypt('foo', $key, $cipher), $key, $cipher
); // foo
//------------------------------------------------------------------------------
// URL或文件名
echo larryphp_decrypt_url(
    larryphp_encrypt_url('foo', $key, $cipher), $key, $cipher
); // foo
//------------------------------------------------------------------------------
// Enjoy it:)
//------------------------------------------------------------------------------
```
使用助手函数`larryphp_encrypt_url`和`larryphp_decrypt_url`，更适合在URL中传输，对于文件名也更适合，因为Base64的编码结果包含了字符`/`，而Unix/Linux系统中`/`是路径分割符。详情请见维基百科[Base64](https://en.wikipedia.org/wiki/Base64#RFC_3548)。
