<?php

namespace MauticPlugin\IrSmsBundle\Integration;

use Mautic\CoreBundle\Form\Type\SortableListType;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\ConfigurationTrait;
use Mautic\IntegrationsBundle\Integration\BC\BcIntegrationSettingsTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormFeatureSettingsInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormSyncInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormFeaturesInterface;
use MauticPlugin\IrSmsBundle\Form\Type\ConfigAuthType;
use MauticPlugin\IrSmsBundle\Form\Type\ConfigFeaturesType;

class IrSmsIntegration extends AbstractIntegration 
      implements 
      ConfigFormInterface, 
      BasicInterface,
      ConfigFormAuthInterface,
      ConfigFormFeatureSettingsInterface, 
      ConfigFormFeaturesInterface
{
    use DefaultConfigFormTrait;
    use ConfigurationTrait;
    use BcIntegrationSettingsTrait;

    public const NAME = 'IrSms';
    protected bool $coreIntegration = true;

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return 'Iran Sms';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    public function getIcon(): string
    {
        return 'plugins/IrSmsBundle/Assets/img/IranSms.png';
    }

    public function getSecretKeys()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ('features' == $formArea) {
            $builder->add(
                'irsms_platform',
                ChoiceType::class,
                [
                    'choices' => IrSmsGateway::GATEWAYS,
                    'label'       => 'mautic.integration.form.platform',
                    'placeholder' => false,
                    'required'    => true,
                ]
            );
            $builder->add(
                'irsms_api_key',
                TextType::class,
                [
                    'label'       => 'mautic.integration.form.api_key',
                    'required'    => true,
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.integration.form.api_key_hint',
                    ],
                ]
            );
            $builder->add(
                'irsms_api_secret',
                TextType::class,
                [
                    'label'       => 'mautic.integration.form.api_secret',
                    'required'    => false,
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.integration.form.api_secret_hint',
                    ],
                ]
            );
            $builder->add(
                'irsms_api_number',
                TextType::class,
                [
                    'label'       => 'mautic.integration.form.api_number',
                    'required'    => false,
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.integration.form.api_number_hint',
                    ],
                ]
            );
            $builder->add(
                'irsms_api_pattern',
                TextType::class,
                [
                    'label'       => 'mautic.integration.form.api_pattern',
                    'required'    => false,
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => [
                        'class'   => 'form-control',
                        'tooltip' => 'mautic.integration.form.api_pattern_hint',
                    ],
                ]
            );
            $builder->add(
                'irsms_api_tokens',
                SortableListType::class,
                [
                    'label'       => 'mautic.integration.form.api_tokens',
                    'required'        => false,
                    'with_labels' => true,
                    'option_required'=>false,
                    'attr'       => [
                        'tooltip' => 'mautic.integration.form.api_tokens_hint',
                    ],
                ]
            );
        }
    }
    
    public function getAuthConfigFormName(): string
    {
        return ConfigAuthType::class;
    }

    public function getFeatureSettingsConfigFormName(): string
    {
        return ConfigFeaturesType::class;
    }

    public function getSupportedFeatures(): array
    {
        return [
            //ConfigFormFeaturesInterface::FEATURE_SYNC => 'mautic.integration.feature.sync',
        ];
    }
}
