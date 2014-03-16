<?php

/**
 * A factory class for building Responses from Guzzle's Response class
 *
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 *
 * @package BitPay\Client
 */

namespace BitPay\Client;

use Guzzle\Http\Message\Response;

class BitPayResponseFactory {

    protected $throwExceptions;

    function __construct($throwExceptions = false)
    {
        $this->throwExceptions = $throwExceptions;
    }

    public function buildFromResponse(Response $response)
    {
        $data = $response->json();

        $response = $this->getResponseObject($response->getEffectiveUrl(), isset($data['error']));

        $response = $this->buildResponseFields($response, $data);

        if ($this->throwExceptions && $response instanceOf ErrorResponse) {

            throw new ErrorResponseException($response);
        }

        return $response;
    }

    public function buildInvoiceResponseFromArray(Array $data)
    {
        $response = $this->getResponseObject('https://bitpay.com/api/invoice', isset($data['error']));

        $response = $this->buildResponseFields($response, $data);

        if ($this->throwExceptions && $response instanceOf ErrorResponse) {

            throw new ErrorResponseException($response);
        }

        return $response;
    }

    private function getResponseObject($url, $error)
    {
        if ($error) {

            return new ErrorResponse();
        }

        $path = parse_url($url, PHP_URL_PATH);
        $components = explode('/',substr($path, 1));
        $action = isset($components[1]) ? $components[1] : null;

        switch($action) {
            case "invoice":

                return new InvoiceResponse();
            default:

                throw new UnknownApiException(sprintf('No viable response can be found for api point %s', $url));
        }
    }

    private function buildResponseFields(BitpayResponse $response, Array $data)
    {
        foreach ($data as $key => $field) {
            if ($key === 'error') {
                $response = $this->expandError($response, $field);
                continue;
            } elseif ($key === 'posData') {
                $response->posData = json_decode(base64_decode($field));
                continue;
            }
            $response->$key = $field;
        }

        return $response;
    }

    private function expandError($response, $field)
    {
        $response->error = true;
        $response->errorType = $field['type'];
        $response->errorMessage = $field['message'];

        return $response;
    }

}
