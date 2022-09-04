<?php

namespace MauticPlugin\IrSmsBundle\Transport\Gateway\Ippanel\Client\Models;

/**
 * Message status template
 */
class Recipient extends Base
{
    /**
     * Recipient number
     * @var string
     */
    public $recipient;

    /**
     * Recipient delivery status
     * @var string
     */
    public $status;
}
