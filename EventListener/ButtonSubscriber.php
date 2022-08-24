<?php

namespace MauticPlugin\IrSmsBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomButtonEvent;
use Mautic\CoreBundle\Templating\Helper\ButtonHelper;
use Mautic\LeadBundle\Entity\Lead;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Mautic\SmsBundle\Entity\Sms;

class ButtonSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router     = $router;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_BUTTONS => ['injectSendMessageButtons', 0],
        ];
    }

    public function injectSendMessageButtons(CustomButtonEvent $event)
    {
        if (0 === strpos($event->getRoute(), 'mautic_contact_')) {
            if ($lead = $event->getItem()) {
                if ($lead instanceof Lead) {
                    $fullname = $lead->getFirstname() . ' ' . $lead->getLastname();
                    $mobile = $lead->getMobile();
                    // @todo: get do not contact and dont show if user asked to not be contacted
                    if (!is_null($mobile)) {
                        $sendSmsButton = [
                            'attr' => [
                                'class'       => 'btn btn-default btn-nospin',
                                'data-toggle' => 'ajaxmodal',
                                'data-target' => '#MauticSharedModal',
                                'data-header' => $this->translator->trans('mautic.iransms.send.title', ['%fullname%' => $fullname, '%mobile%' => $mobile]),
                                'href'        => $this->router->generate('mautic_irsms_action', ['objectAction' => 'sendForContact', 'objectId' => $lead->id]),
                            ],
                            'iconClass' => 'fa fa-send-o',
                            'btnText'   => $this->translator->trans('mautic.iransms.send.button'),
                            'primary'   => false,
                        ];

                        $event->addButton(
                            $sendSmsButton,
                            ButtonHelper::LOCATION_LIST_ACTIONS,
                        )->addButton(
                            $sendSmsButton,
                            ButtonHelper::LOCATION_PAGE_ACTIONS,
                        );
                    }
                }
            }
        }
    }
}
