<?php


namespace MauticPlugin\IrSmsBundle\Transport\Gateway\Ippanel;

use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use Exception;
use CurlHandle;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use Monolog\Logger;
use MauticPlugin\IrSmsBundle\Transport\Gateway\Ippanel\Client\HTTPClient;

/**
 * An implementation of ISMSGateway for Nexmo Provider
 */
class IppanelGateway implements IrSmsGateway
{
    /** @var Configuration */
    protected $config;

    protected $client = null;
    /** @var Logger */
    protected $logger;

    /**
     * Client version for setting in api call user agent header
     * @var string
     */
    const CLIENT_VERSION = "1.0.1";

    /**
     * Default timeout for api call
     * @var int
     */
    const DEFAULT_TIMEOUT = 30;

    /**
     * Api endpoint
     * @var string
     */
    const ENDPOINT = "http://rest.ippanel.com";

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
        $userAgent = sprintf("IPPanel/ApiClient/%s PHP/%s",  self::CLIENT_VERSION, phpversion());
        $this->client = new HTTPClient(self::ENDPOINT, self::DEFAULT_TIMEOUT, [
                sprintf("Authorization: AccessKey %s", $this->config->getApiKey()),
                sprintf("User-Agent: %s", $userAgent),
            ]);
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
          $res = $this->client->post("/v1/messages", [
              "originator" =>  $this->config->getApiNumber(),
              "recipients" => [$number],
              "message" => $message,
          ]);

          if (!isset($res->data->bulk_id)) {
              throw new Exception("returned response not valid", 1);
          }

          return $res->data->bulk_id;
        } catch (Exception $ex) {
            $this->logger->error('Failed sending IrSmsBundle message', [
                'platgorm' => self::class,
                'exception' => $ex->getMessage(),
                'code' => $ex->getCode(),
            ]);
        }
    }
}
