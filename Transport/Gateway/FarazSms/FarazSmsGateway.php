<?php


namespace MauticPlugin\IrSmsBundle\Transport\Gateway\FarazSms;

use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use Exception;
use CurlHandle;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use Monolog\Logger;

/**
 * An implementation of ISMSGateway for Nexmo Provider
 */
class FarazSmsGateway implements IrSmsGateway
{
    /** @var Configuration */
    protected $config;

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
        $this->client = curl_init("https://ippanel.com/services.jspd");
        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
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
            curl_setopt($this->client, CURLOPT_POSTFIELDS, [
                'uname' => $this->config->getApiKey(),
                'pass' =>  $this->config->getApiSecret(),
                'from' =>  $this->config->getApiNumber(),
                'message' => $message,
                'to' => json_encode($number),
                'op' => 'send'
            ]);
            $response = curl_exec($this->client);
            return $response;
        } catch (Exception $ex) {
            $this->logger->error('Failed sending IrSmsBundle message', [
                'platgorm' => self::class,
                'exception' => $ex->getMessage(),
                'code' => $ex->getCode(),
            ]);
        }
    }
}
