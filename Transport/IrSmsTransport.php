<?php

namespace MauticPlugin\IrSmsBundle\Transport;

use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Sms\TransportInterface;
use Mautic\LeadBundle\Model\DoNotContact;
use MauticPlugin\IrSmsBundle\Integration\Configuration;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use Monolog\Logger;

class IrSmsTransport implements TransportInterface
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var string
     */
    private $keywordField;

    /**
     * @var DoNotContact
     */
    private $doNotContactService;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var bool
     */
    private $connectorConfigured = false;

    /**
     * SlooceTransport constructor.
     */
    public function __construct(
        Configuration $config,
        Logger $logger,
        DoNotContact $doNotContactService
    ) {
        $this->logger              = $logger;
        $this->doNotContactService = $doNotContactService;
        $this->config  = $config;
    }

    /**
     * @param string $content
     *
     * @return bool
     */
    public function sendSms(Lead $lead, $content)
    {
        $targetPlatform = $this->config->getPlatform();
        if (isset(IrSmsGateway::GATEWAYS[$targetPlatform])) {
            $platformClass = IrSmsGateway::GATEWAYS[$targetPlatform];
            /** @var IrSmsGateway $platform */
            $platform = new $platformClass();
            $platform->config($this->config, $this->logger);
            $platform->init();
            $platform->sendSingle($lead->getMobile(), $content, []);
            return true;
        }

        return false;
    }
}
