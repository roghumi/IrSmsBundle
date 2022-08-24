<?php

namespace MauticPlugin\IrSmsBundle\Transport\Gateway\SMSIR;

use Exception;
use MauticPlugin\IrSmsBundle\Transport\Gateway\SmsIr\Client\SendMessage;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use Monolog\Logger;

/**
 * An implementation of ISMSGateway for FaraPayamak Provider
 */
class SmsIrSimpleGateway implements IrSmsGateway
{
    /** @var Configuration */
    protected $config;
    /** @var SendMessage */
    protected $client = null;
    /** @var Logger */
    protected $logger;

    /**
     * Undocumented function
     *
     * @param Configuration $conf
     * @param Logger $logger
     * @return void
     */
    public function config(
        Configuration $conf,
        Logger $logger,
    ) {
        $this->logger = $logger;
        $this->config = $conf;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function init()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new SendMessage(
            $this->config->getApiKey(),
            $this->config->getApiSecret(),
            $this->config->getApiNumber(),
        );
    }

    /**
     * @param String $number
     * @param String $message
     * @param array $options
     *
     * @return null|string
     */
    public function sendSingle(String $number, String $message, array $options)
    {
        try {
            return $this->client->SendMessage(
                [$number],
                [$message]
            );
        } catch (Exception $ex) {
            $this->logger->error('Failed sending IrSmsBundle message', [
                'platgorm' => self::class,
                'exception' => $ex->getMessage(),
                'code' => $ex->getCode(),
            ]);
        }
    }
}
