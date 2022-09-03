<?php

declare(strict_types=1);

namespace MauticPlugin\IrSmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigAuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $clientSecret   = null;
        $configProvider = $options['integration'];
        if ($configProvider->getIntegrationConfiguration() && $configProvider->getIntegrationConfiguration()->getApiKeys()) {
            $data         = $configProvider->getIntegrationConfiguration()->getApiKeys();
            $clientSecret = $data['client_secret'] ?? null;
        }

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
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'integration' => null,
            ]
        );
    }
}
