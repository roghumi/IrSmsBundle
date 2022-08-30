<?php


namespace MauticPlugin\IrSmsBundle\Transport\Gateway\KaveNegar;

use Exception;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use Monolog\Logger;

/**
 * An implementation of ISMSGateway for Kavehnegar Provider
 */
class KaveNegarSimpleGateway implements IrSmsGateway
{
    /** @var Configuration */
    protected $config;
    /** @var \Kavenegar\KavenegarApi */
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
        Logger $logger
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
        $this->client = new \Kavenegar\KavenegarApi($this->config->getApiKey());
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
            return $this->client->Send(
                $this->config->getApiNumber(),
                [$number],
                $message
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
