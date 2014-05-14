<?php

/**
 * An exception thrown for invoices trying to be created with an invalid Price value
 * 
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox 
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 * 
 * @package BitPay\Client
 */

namespace BitPay\Client;

class InvoicePriceException extends \InvalidArgumentException {
    
}
