<?php

/**
 * A response returned that encompasses all the different kinds of errors
 *
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 *
 * @package BitPay\Client
 */
namespace BitPay\Client;

class ErrorResponse implements BitPayResponse {

    const LIMIT_EXCEEDED = 'limitExceeded';
    const VALIDATION_ERROR = 'validationError';
    const UNAUTHORIZED = 'unauthorized';
    const NOT_FOUND = 'notFound';

    /** @var boolean $error a flag that is always true in the class */
    public $error = true;
    /** @var String $errorMessage a descriptive message of the error */
    public $errorMessage;
    /** @var String $errorType a short description of the error */
    public $errorType;
}
