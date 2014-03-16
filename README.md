BitPayClient
=========

The purpose of this library is to be an object orientated alternative to the official [BitPay PHP library].

The library is designed to make it easier to integrate BitPay in to more heavy PHP frameworks that use composer as a package manager such as Symfony2 and Laravel.

The library is written by myself, [Peter Fox].

Version
----

1.1.0 - 16th March 2014 - Allows for throwing exception when an error api response is returned
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

Usage
------

The most basic use is as follows:

```php
$client = new BitPayClient('YOUR-API-KEY');

$invoiceResponse = $client->createInvoice(0.0001, 'BTC');
```

You can supply further parameters for creating an invoice via a third array parameter (to see all possible parameters read the BitPay API):

```php
$client = new BitPayClient('YOUR-API-KEY');

$invoiceResponse = $client->createInvoice(0.0001, 'BTC', ['redirectUrl' => 'http://somewhere']);
```

You can also supply a PosData value as an array and it will be sent as a Base64 encoded string (please note that other data will be modified to be base64 as well):

```php
$client = new BitPayClient('YOUR-API-KEY');

$invoiceResponse = $client->createInvoice(0.0001, 'BTC', ['posData' => ['some_id' => 100]]);
```

The library also allows for making responses from Array (like from a $_POST):

```php
$client = new BitPayClient('YOUR-API-KEY');

$invoiceData = [
            'id'=> 'CNRWBUPUZs9foP2ysZBBc',
            'url'=> 'https://bitpay.com/invoice?CNRWBUPUZs9foP2ysZBBc',
            'status' => 'new',
            'btcPrice' => '0.0001',
            'price' => 0.0001,
            'currency' => 'BTC',
            'invoiceTime' => 1391301679184,
            'expirationTime' => 1391302579184,
            'currentTime' => 1391302121888
            ];

$invoiceResponse = $client->getInvoiceFromArray($invoiceData);
```
There's also the functionality of getting an invoice from BitPay's API if you so wish:
```php
$client = new BitPayClient('YOUR-API-KEY');

$invoiceGetResponse = $client->getInvoice('CNRWBUPUZs9foP2ysZBBc');
```

Testing
--------

After cloning and installing this repo you can run */vendor/bin/phpunit* in the root of the project and test it.

You will have to create a file called apikey.txt in the root directory of the project containing your api to actually run these tests.

The testing of this project uses [PHP-VCR] which is useful for running the tests multiple times as testCreateInvoice_LimitExceeded() test will cause BitPay to send you an email as a warning which can be a bit tedious. The fixtures are all stored in test/fixtures/.

License
----

GNU GPL Version 3

[BitPay PHP library]:https://github.com/bitpay/php-client
[PHP-VCR]:https://github.com/php-vcr/php-vcr
[bitpay]:http://www.bitpay.com
[Peter Fox]:http://www.peterfox.me
