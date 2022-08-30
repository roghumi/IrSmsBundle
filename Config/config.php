<?php

return [
    'name'        => 'Iran Sms Providers',
    'description' => 'Iranian SMS Providers.',
    'author'      => 'Peynman',
    'version'     => '1.0.0',

    'services' => [
        'events' => [
            'mautic.surgebundle.button.subscriber' => [
                'class'     => \MauticPlugin\IrSmsBundle\EventListener\ButtonSubscriber::class,
                'arguments' => [
                    'router',
                    'translator',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.lead_quicksms' => [
                'class'     => \MauticPlugin\IrSmsBundle\Form\SendSmsToContact::class,
                'arguments' => ['mautic.helper.user'],
            ],
        ],
        'other' => [
            'mautic.sms.irsms.configuration' => [
                'class'        => \MauticPlugin\IrSmsBundle\Integration\Configuration::class,
                'arguments'    => [
                    'mautic.integrations.helper',
                ],
            ],
            'mautic.sms.transport.irsms' => [
                'class'        => \MauticPlugin\IrSmsBundle\Transport\IrSmsTransport::class,
                'arguments'    => [
                    'mautic.sms.irsms.configuration',
                    'monolog.logger.mautic',
                    'mautic.lead.model.dnc',
                ],
                'tag'          => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'IrSms',
                ],
                'serviceAliases' => [
                    'irsms_api',
                    'mautic.irsms.api',
                ],
            ],
            'mautic.sms.irsms.callback' => [
                'class'     => \MauticPlugin\IrSmsBundle\Integration\IranSmsCallback::class,
                'arguments' => [
                    'mautic.sms.helper.contact',
                    'mautic.sms.irsms.configuration',
                ],
                'tag'   => 'mautic.sms_callback_handler',
            ],
        ],
        'integrations' => [
            'mautic.integration.irsms' => [
                'class'     => \MauticPlugin\IrSmsBundle\Integration\IrSmsIntegration::class,
                'tags'  => [
                    'mautic.config_integration',
                    'mautic.basic_integration',
                ],
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                ],
            ],
        ],
    ],
    'routes'      => [
        'main'   => [
            'mautic_irsms_action' => [
                'path'       => '/irsms/{objectAction}/{objectId}',
                'controller' => 'IrSmsBundle:Sms:execute',
            ],
        ],
        'public' => [],
        'api'    => [],
    ],
    'menu'        => [
        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route'    => 'mautic_sms_index',
                    'access'   => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent'   => 'mautic.core.channels',
                    'checks'   => [
                        'integration' => [
                            'IrSms' => [
                                'enabled' => true,
                            ],
                        ],
                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'parameters' => [
        'irsms_enabled' => false,
        'irsms_platform' => null,
        'irsms_api_key' => null,
        'irsms_api_secret' => null,
        'irsms_api_number' => null,
        'irsms_api_pattern' => null,
        'irsms_api_tokens' => [],
    ],
];
