<?php
/**
 * An exception thrown by the BitPayResponseFactory when a valid response object 
 * can't be made because the invoice is incorrect
 * 
 * @author Peter Fox <peterfox@peterfox.me>
 * @copyright 2014 Peter Fox 
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/peterfox/BitPayClient
 * 
 * @package BitPay\Client
 */

namespace BitPay\Client;

class UnknownApiException extends \Exception {
    
}
