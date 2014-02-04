<?php

/**
 * The Response returned by the BitPayClient for a successful API call
 * 
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox 
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 * 
 * @package BitPay\Client
 */

namespace BitPay\Client;

class InvoiceResponse implements BitPayResponse {
    
    /** @var boolean $error a flag that is always false in the class */
    public $error = true;
    /** @var String $id the unique ID of the invoice */
    public $id;
    /** @var String $url the url where you can view the invoice to be able to pay for it etc. */
    public $url;
    /** @var String $status the status of the invoice */
    public $status;
    /** @var String $btcPrice the invoice total in bitcoins */
    public $btcPrice;
    /** @var int $price the invoice total in the requested currency */
    public $price;
    /** @var String $currency the currency used for the making the invoice */
    public $currency;
    /** @var int $invoiceTime the time the invoice was made */
    public $invoiceTime;
    /** @var int $expirationTime the time the invoice will expire */
    public $expirationTime;
    /** @var int $currentTime the time of the BitPay servers */
    public $currentTime;
}
