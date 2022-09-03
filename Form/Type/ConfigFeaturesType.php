<?php

declare(strict_types=1);

namespace MauticPlugin\IrSmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Mautic\CoreBundle\Form\Type\SortableListType;
use Symfony\Component\Form\FormBuilderInterface;
use MauticPlugin\IrSmsBundle\Transport\Gateway\IrSmsGateway;

class ConfigFeaturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
