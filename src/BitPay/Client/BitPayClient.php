<?php

/**
 * BitPayClient is an OOP PHP implementation for interacting with the BitPay (https://bitpay.com) API
 *
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 *
 * @package BitPay\Client
 */

namespace BitPay\Client;

use Guzzle\Http\Client;
use Guzzle\Common\Collection;

/**
 * BitPayClient is an OOP PHP implementation for interacting with the BitPay (https://bitpay.com) API
 */
class BitPayClient
{

    protected $client;

    protected $apiCollection;
    protected $invoiceCollection;

    protected $responseFactory;

    protected $apiKey;

   /**
    * Constructs the BitPayClient class
    * @api
    *
    * @param String $apiKey The API key required for interacting with the API
    * @param Array $defaults An array of default values to be used in post requests
    * @param Array $invoiceParameters An array of default values to be used for invoices
    * @param boolean $throwResponseExceptions A switch which will cause the client to throw exception on Error response from api calls
    */
    public function __construct($apiKey, Array $defaults = [],
            Array $invoiceParameters = [], $throwResponseExceptions = false)
    {
        $this->client = new Client('https://bitpay.com/api');
        //$this->client->setDefaultOption('header/Content-Type', 'application/json');
        $this->apiKey = $apiKey;
        $this->apiCollection = $this->createDefaultPostParameters($defaults);
        $this->invoiceCollection = $this->createDefaultPostParameters($invoiceParameters);

        $this->responseFactory = new BitPayResponseFactory($throwResponseExceptions);
    }

   /**
    * Gets the API key being used by the client
    * @api
    *
    * @return String The API key required for interacting with the API
    */
    public function getApiKey()
    {
        return $this->apiKey;
    }

   /**
    * Sets the API key being used by the client
    * @api
    *
    * @param String $apiKey The API key required for interacting with the API
    */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

   /**
    * Creates an invoice on BitPay which the user can be forwarded to
    * @api
    *
    * @param float $price The price of the item that you're invoicing
    * @param String $currency The three letter name of the currency, e.g. BTC,USD,GBP see them all at https://bitpay.com/bitcoin-exchange-rates
    * @param Array $extraParams An array of the optional options that can be used to create an invoice
    *
    * @return BitPayResponse
    */
    public function createInvoice($price, $currency, Array $extraParams = [])
    {
        $this->validateCurrency($currency);
        $this->validatePrice($price);
        $params = $this->buildInvoicePostParams($price, $currency, $extraParams, $this->invoiceCollection->toArray());
        $bitpayResponse = $this->fireOffPostRequest('invoice', $params, [
            'auth' => [$this->apiKey, ''],
            'exceptions' => false,
            ]);

        return $bitpayResponse;
    }

   /**
    * Retrives an invoice from BitPay which was generated previously
    * @api
    *
    * @param String $id The id of a previously generated invoice
    *
    * @return BitPayResponse
    */
    public function getInvoice($id)
    {
        $bitpayResponse = $this->fireOffGetRequest('invoice/'.$id, [
            'auth' => [$this->apiKey, ''],
            'exceptions' => false,
            ]);

        return $bitpayResponse;
    }

   /**
    * Builds an InvoiceResponse from an array, this is useful for building objects from BitPay HTTP notifications
    * @api
    *
    * @param Array $data the Data excepted to be found in a Invoice Response
    *
    * @return BitPayResponse
    */
    public function getInvoiceFromArray(Array $data)
    {
        return $this->responseFactory->buildInvoiceResponseFromArray($data);
    }

    private function createDefaultPostParameters(Array $parameters)
    {
        $collection = new Collection();

        return $collection->fromConfig($parameters);
    }

    private function buildInvoicePostParams($price, $currency, Array $extraParams = [], Array $defaults = [])
    {
        if (isset($extraParams['posData'])) {
            $extraParams['posData'] = $this->transformPosData($extraParams['posData']);
        }
        $params = $this->apiCollection->fromConfig($extraParams, $defaults);

        $params->add('price', $price);
        $params->add('currency', strtoupper($currency));

        return $params;
    }

    private function transformPosData($posData)
    {
        if (!is_array($posData)) {
            $posData = ['data'=> $posData, 'transformed'=> true];
        }
        $posData = base64_encode(json_encode($posData));
        if (strlen($posData) > 100) {

            throw new InvoicePosDataException;
        }

        return $posData;
    }

    private function fireOffPostRequest($action, Collection $params, Array $options = [])
    {
        $response = $this->client->post($action, ['Content-Type' => 'application/json'], json_encode($params->toArray()), $options)->send();

        return $this->responseFactory->buildFromResponse($response);
    }

    private function fireOffGetRequest($action, Array $options = [])
    {
        $response = $this->client->get($action, [], $options)->send();

        return $this->responseFactory->buildFromResponse($response);
    }

    protected function validatePrice($price)
    {
        if (!is_numeric($price)) {

            throw new InvoicePriceException;
        }
    }

    protected function validateCurrency($currency)
    {
        if (!in_array(strtoupper($currency), $this->validCurrencies())) {

            throw new InvoiceCurrencyException;
        }
    }

    private function validCurrencies()
    {
        return ['BTC','USD','EUR','GBP','JPY','CAD','AUD','CNY','CHF','SEK','NZD',
            'KRW','AED','AFN','ALL','AMD','ANG','AOA','ARS','AWG','AZN','BAM',
            'BBD','BDT','BGN','BHD','BIF','BMD','BND','BOB','BRL','BSD','BTN',
            'BWP','BYR','BZD','CDF','CLF','CLP','COP','CRC','CVE','CZK','DJF',
            'DKK','DOP','DZD','EEK','EGP','ETB','FJD','FKP','GEL','GHS','GIP',
            'GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS',
            'INR','IQD','ISK','JEP','JMD','JOD','KES','KGS','KHR','KMF','KWD',
            'KYD','KZT','LAK','LBP','LKR','LRD','LSL','LTL','LVL','LYD','MAD',
            'MDL','MGA','MKD','MMK','MNT','MOP','MRO','MUR','MVR','MWK','MXN',
            'MYR','MZN','NAD','NGN','NIO','NOK','NPR','OMR','PAB','PEN','PGK',
            'PHP','PKR','PLN','PYG','QAR','RON','RSD','RUB','RWF','SAR','SBD',
            'SCR','SDG','SGD','SHP','SLL','SOS','SRD','STD','SVC','SYP','SZL',
            'THB','TJS','TMT','TND','TOP','TRY','TTD','TWD','TZS','UAH','UGX',
            'UYU','UZS','VEF','VND','VUV','WST','XAF','XAG','XAU','XCD','XOF',
            'XPF','YER','ZAR','ZMW','ZWL'];
    }

}
