<?php


namespace MauticPlugin\IrSmsBundle\Transport\Gateway;

use MauticPlugin\IrSmsBundle\Integration\Configuration;
use MauticPlugin\IrSmsBundle\Transport\Gateway\FaraPayamak\FaraPayamakGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\FarazSms\FarazSmsGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\KaveNegar\KaveNegarSimpleGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\KaveNegar\KaveNegarLookupGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\MizbanSms\MizbanSmsGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\SmsIr\SmsIrLookupGateway;
use MauticPlugin\IrSmsBundle\Transport\Gateway\SmsIr\SMSIRSimpleGateway;
use Monolog\Logger;

/**
 * An SMS Service gateway interface,
 *   is responsible for actully sending sms messages to destination
 *   in real world we will use a third party provider thoagh
 */
interface IrSmsGateway
{
    public const GATEWAYS = [
        'mautic.integration.irsms.mizbansms' => MizbanSmsGateway::class,
        'mautic.integration.irsms.farapayamak' => FaraPayamakGateway::class,
        'mautic.integration.irsms.kavenegar_send' => KaveNegarSimpleGateway::class,
        'mautic.integration.irsms.kavenegar_lookup' => KaveNegarLookupGateway::class,
        'mautic.integration.irsms.farazsms' => FarazSmsGateway::class,
        'mautic.integration.irsms.smsir_lookup' => SmsIrLookupGateway::class,
        'mautic.integration.irsms.smsir_send' => SmsIrSimpleGateway::class,
    ];

    /**
     * Undocumented function
     *
     * @param array $conf
     * @return void
     */
    public function config(
        Configuration $conf,
        Logger $logger
    );

    /**
     * Undocumented function
     *
     * @param String $number
     * @param String $message
     * @param array $options
     * @return void
     */
    public function sendSingle(String $number, String $message, array $options);

    /**
     * Undocumented function
     *
     * @return void
     */
    public function init();
}
