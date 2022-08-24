<?php

namespace MauticPlugin\IrSmsBundle\Form;

use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\CoreBundle\Helper\UserHelper;
use Mautic\EmailBundle\Form\Type\EmailListType;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Form\Type\SmsListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendSmsToContact extends AbstractType
{
    /**
     * @var UserHelper
     */
    private $userHelper;

    public function __construct(UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CleanFormSubscriber(['body' => 'html']));

        $builder->add(
            'template',
            SmsListType::class,
            [
                'label'      => 'mautic.iransms.send.choose',
                'label_attr' => ['class' => 'control-label'],
                'required'   => true,
                'attr'       => [
                    'class'    => 'form-control',
                ],
                'multiple' => false,
            ]
        );

        $builder->add('buttons', FormButtonsType::class, [
            'apply_text'  => false,
            'save_text'   => 'mautic.email.send',
            'save_class'  => 'btn btn-primary',
            'save_icon'   => 'fa fa-send',
            'cancel_icon' => 'fa fa-times',
        ]);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'lead_quicksms';
    }
}
