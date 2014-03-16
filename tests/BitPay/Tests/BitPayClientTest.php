<?php

/**
 * A test suite for checking that changes haven't broken the BitPayClient
 *
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 *
 * @package BitPay\Test
 */

namespace BitPay\Tests;

use BitPay\Client\BitPayClient;
use BitPay\Client\ErrorResponse;

class BitPayClientTest extends \PHPUnit_Framework_TestCase {

    protected $client;
    public static $apiKey;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        $apiFile = getcwd().'/apikey.txt';

        if(file_exists($apiFile)) {
            self::$apiKey = file_get_contents($apiFile);
        } else {
            exit("*** Please create in the projects directory a apikey.txt file containing your "
                    . "BitPay API key which can be used to run these tests ***\n\n");
        }
    }

    /**
     * @group standard
     * @vcr GetInvoice_Working
     */
    public function testGetInvoice_Working()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceCreateResponse = $this->client->createInvoice(0.0001, 'BTC');

        $invoiceGetResponse = $this->client->getInvoice($invoiceCreateResponse->id);

        $this->assertInstanceOf('BitPay\Client\InvoiceResponse', $invoiceGetResponse);

        $this->assertObjectHasAttribute('id', $invoiceGetResponse);
        $this->assertObjectHasAttribute('url', $invoiceGetResponse);
        $this->assertObjectHasAttribute('status', $invoiceGetResponse);
        $this->assertObjectHasAttribute('btcPrice', $invoiceGetResponse);
        $this->assertObjectHasAttribute('price', $invoiceGetResponse);
        $this->assertObjectHasAttribute('currency', $invoiceGetResponse);
        $this->assertObjectHasAttribute('invoiceTime', $invoiceGetResponse);
        $this->assertObjectHasAttribute('expirationTime', $invoiceGetResponse);
        $this->assertObjectHasAttribute('currentTime', $invoiceGetResponse);

        $this->assertEquals($invoiceCreateResponse->id, $invoiceGetResponse->id);
        $this->assertEquals($invoiceCreateResponse->url, $invoiceGetResponse->url);
        $this->assertEquals($invoiceCreateResponse->status, $invoiceGetResponse->status);
        $this->assertEquals($invoiceCreateResponse->btcPrice, $invoiceGetResponse->btcPrice);
        $this->assertEquals($invoiceCreateResponse->price, $invoiceGetResponse->price);
        $this->assertEquals($invoiceCreateResponse->currency, $invoiceGetResponse->currency);
        $this->assertEquals($invoiceCreateResponse->invoiceTime, $invoiceGetResponse->invoiceTime);
        $this->assertEquals($invoiceCreateResponse->expirationTime, $invoiceGetResponse->expirationTime);
    }

	/**
     * @group standard
     * @vcr GetInvoice_IdFailiure
     */
    public function testGetInvoice_IdFailiure()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceResponse = $this->client->getInvoice('notavalidinvoice');

        $this->assertInstanceOf('BitPay\Client\ErrorResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('error', $invoiceResponse);
        $this->assertObjectHasAttribute('errorType', $invoiceResponse);
        $this->assertObjectHasAttribute('errorMessage', $invoiceResponse);

        $this->assertTrue($invoiceResponse->error);
        $this->assertEquals(ErrorResponse::NOT_FOUND, $invoiceResponse->errorType);
        $this->assertEquals('Invoice not found', $invoiceResponse->errorMessage);
    }

    /**
     * @group standard
     * @vcr CreateInvoice_Working
     */
    public function testCreateInvoice_Working()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceResponse = $this->client->createInvoice(0.0001, 'BTC');

        $this->assertInstanceOf('BitPay\Client\InvoiceResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('id', $invoiceResponse);
        $this->assertObjectHasAttribute('url', $invoiceResponse);
        $this->assertObjectHasAttribute('status', $invoiceResponse);
        $this->assertObjectHasAttribute('btcPrice', $invoiceResponse);
        $this->assertObjectHasAttribute('price', $invoiceResponse);
        $this->assertObjectHasAttribute('currency', $invoiceResponse);
        $this->assertObjectHasAttribute('invoiceTime', $invoiceResponse);
        $this->assertObjectHasAttribute('expirationTime', $invoiceResponse);
        $this->assertObjectHasAttribute('currentTime', $invoiceResponse);

        $this->assertEquals(0.0001, $invoiceResponse->btcPrice);
        $this->assertEquals('BTC', $invoiceResponse->currency);
    }

    /**
     * @group standard
     */
    public function testGetInvoiceFromArray_Working()
    {
        $this->client = new BitPayClient(self::$apiKey);

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

        $invoiceResponse = $this->client->getInvoiceFromArray($invoiceData);

        $this->assertInstanceOf('BitPay\Client\InvoiceResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('id', $invoiceResponse);
        $this->assertObjectHasAttribute('url', $invoiceResponse);
        $this->assertObjectHasAttribute('status', $invoiceResponse);
        $this->assertObjectHasAttribute('btcPrice', $invoiceResponse);
        $this->assertObjectHasAttribute('price', $invoiceResponse);
        $this->assertObjectHasAttribute('currency', $invoiceResponse);
        $this->assertObjectHasAttribute('invoiceTime', $invoiceResponse);
        $this->assertObjectHasAttribute('expirationTime', $invoiceResponse);
        $this->assertObjectHasAttribute('currentTime', $invoiceResponse);

        $this->assertEquals('CNRWBUPUZs9foP2ysZBBc', $invoiceResponse->id);
        $this->assertEquals(0.0001, $invoiceResponse->btcPrice);
        $this->assertEquals('BTC', $invoiceResponse->currency);
    }

    /**
     * @group standard
     * @vcr CreateInvoice_WorkingPosData
     */
    public function testCreateInvoice_WorkingPosData()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceResponse = $this->client->createInvoice(0.0001, 'BTC', [
            'posData' => [
                'test1' => 1,
                'test2' => 'a',
                'test3' => 0.1,
                'test4' => true,
            ]
        ]);

        $this->assertInstanceOf('BitPay\Client\InvoiceResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('id', $invoiceResponse);
        $this->assertObjectHasAttribute('url', $invoiceResponse);
        $this->assertObjectHasAttribute('status', $invoiceResponse);
        $this->assertObjectHasAttribute('btcPrice', $invoiceResponse);
        $this->assertObjectHasAttribute('price', $invoiceResponse);
        $this->assertObjectHasAttribute('currency', $invoiceResponse);
        $this->assertObjectHasAttribute('invoiceTime', $invoiceResponse);
        $this->assertObjectHasAttribute('expirationTime', $invoiceResponse);
        $this->assertObjectHasAttribute('currentTime', $invoiceResponse);
        $this->assertObjectHasAttribute('posData', $invoiceResponse);

        $this->assertObjectHasAttribute('test1',$invoiceResponse->posData);
        $this->assertObjectHasAttribute('test2',$invoiceResponse->posData);
        $this->assertObjectHasAttribute('test3',$invoiceResponse->posData);
        $this->assertObjectHasAttribute('test4',$invoiceResponse->posData);

        $this->assertEquals(1,$invoiceResponse->posData->test1);
        $this->assertEquals('a',$invoiceResponse->posData->test2);
        $this->assertEquals(0.1,$invoiceResponse->posData->test3);
        $this->assertEquals(true,$invoiceResponse->posData->test4);

        $this->assertEquals(0.0001, $invoiceResponse->btcPrice);
        $this->assertEquals('BTC', $invoiceResponse->currency);
    }

    /**
     * @group standard
     */
    public function testCreateInvoice_PosDataException()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $this->setExpectedException('BitPay\Client\InvoicePosDataException');

        $this->client->createInvoice(0.0001, 'BTC', [
            'posData' => [
                'test1' => 'A passthru variable provided by the merchant and '
                . 'designed to be used by the merchant to correlate the invoice '
                . 'with an order or other object in their system. Maximum '
                . 'string length is 100 characters.'
            ]
        ]);
    }

    /**
     * @group standard
     */
    public function testCreateInvoice_PriceException()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $this->setExpectedException('BitPay\Client\InvoicePriceException');

        $this->client->createInvoice('wrong type', 'BTC');
    }

    /**
     * @group standard
     */
    public function testCreateInvoice_CurrencyException()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $this->setExpectedException('BitPay\Client\InvoiceCurrencyException');

        $this->client->createInvoice(0.0001, 'Not A Currency');
    }

    /**
     * @group standard
     * @vcr CreateInvoice_AuthFailiure
     */
    public function testCreateInvoice_AuthFailiure()
    {
        $this->client = new BitPayClient('');

        $invoiceResponse = $this->client->createInvoice(0.0001, 'BTC');

        $this->assertInstanceOf('BitPay\Client\ErrorResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('error', $invoiceResponse);
        $this->assertObjectHasAttribute('errorType', $invoiceResponse);
        $this->assertObjectHasAttribute('errorMessage', $invoiceResponse);

        $this->assertTrue($invoiceResponse->error);
        $this->assertEquals(ErrorResponse::UNAUTHORIZED, $invoiceResponse->errorType);
        $this->assertEquals('invalid api key', $invoiceResponse->errorMessage);
    }

    /**
     * @group standard
     * @vcr CreateInvoice_AuthFailiureException
     */
    public function testCreateInvoice_AuthFailiureException()
    {
        $this->client = new BitPayClient('', [], [], true);

        $this->setExpectedException('BitPay\Client\ErrorResponseException', 'invalid api key');

        $invoiceResponse = $this->client->createInvoice(0.0001, 'BTC');
    }

    /**
     * @group standard
     * @vcr CreateInvoice_PriceFailiure
     */
    public function testCreateInvoice_PriceFailiure()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceResponse = $this->client->createInvoice(0, 'GBP');

        $this->assertInstanceOf('BitPay\Client\ErrorResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('error', $invoiceResponse);
        $this->assertObjectHasAttribute('errorType', $invoiceResponse);
        $this->assertObjectHasAttribute('errorMessage', $invoiceResponse);

        $this->assertTrue($invoiceResponse->error);
        $this->assertEquals(ErrorResponse::VALIDATION_ERROR, $invoiceResponse->errorType);
        $this->assertEquals('One or more fields is invalid', $invoiceResponse->errorMessage);
    }

    /**
     * @group limit
     * @vcr CreateInvoice_LimitExceeded
     */
    public function testCreateInvoice_LimitExceeded()
    {
        $this->client = new BitPayClient(self::$apiKey);

        $invoiceResponse = $this->client->createInvoice(100, 'BTC');

        $this->assertInstanceOf('BitPay\Client\ErrorResponse', $invoiceResponse);

        $this->assertObjectHasAttribute('error', $invoiceResponse);
        $this->assertObjectHasAttribute('errorType', $invoiceResponse);
        $this->assertObjectHasAttribute('errorMessage', $invoiceResponse);

        $this->assertTrue($invoiceResponse->error);
        $this->assertEquals(ErrorResponse::LIMIT_EXCEEDED, $invoiceResponse->errorType);
        $this->assertEquals('Invoice not created due to account limits, please check your approval levels', $invoiceResponse->errorMessage);
    }

}
