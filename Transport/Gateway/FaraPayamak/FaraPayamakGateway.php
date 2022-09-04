<?php

namespace MauticPlugin\IrSmsBundle\Transport\Gateway\FaraPayamak;

use Exception;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use Monolog\Logger;
use SoapClient;

/**
 * An implementation of ISMSGateway for FaraPayamak Provider
 */
class FaraPayamakGateway implements IrSmsGateway
{
    /** @var Configuration */
    protected $config;
    /** @var SoapClient */
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
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new SoapClient('http://api.payamak-panel.com/post/Send.asmx?wsdl');
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
        $data = [
            'username' => $this->config->getApiKey(),
            'password' => $this->config->getApiSecret(),
            'from' => $this->config->getApiNumber(),
            'to' => $number,
            'text' => $message,
            'isflash' => false,
        ];
        $result = $this->client->SendSimpleSMS2($data);
        return $result;
    }
}
