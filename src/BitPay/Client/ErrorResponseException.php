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

class ErrorResponseException extends \Exception
{
    protected $response;

    public function __construct(ErrorResponse $response)
    {
        parent::__construct($response->errorMessage);
        $this->response = $response;
    }

    public function getErrorResponse()
    {
        return $this->response;
    }
}
