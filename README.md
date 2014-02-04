BitPayClient
=========

The purpose of this library is to be an object orientated alternative to the official [BitPay PHP library].

The library is designed to make it easier to intergrate BitPay in to more heavy PHP frameworks that use composer as a package manager such as Symfony2 and Laravel.

The library is written by myself, [Peter Fox].

Version
----

1.0.0 - 5th Feburary 2014

Installation
--------------

Add to your projects composer.json

```json
{
    "require": {
        "peterfox/bitpayclient":"1.0.*"
    }
}
```

Then run composer install or composer update to install this package.

Testing
--------

After cloning and installing this repo you can run */vendor/bin/phpunit* in the root of the project and test it.

You will have to create a file called apikey.txt in the root directory of the project containing your api to actually run these tests.

License
----

GNU GPL Version 3

[BitPay PHP library]:https://github.com/bitpay/php-client
[bitpay]:http://www.bitpay.com
[Peter Fox]:http://www.peterfox.me