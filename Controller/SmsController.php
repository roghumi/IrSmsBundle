<?php

namespace MauticPlugin\IrSmsBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\EmailBundle\Form\Type\BatchSendType;
use MauticPlugin\IrSmsBundle\Form\SendSmsToContact;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;

class SmsController extends FormController
{
    /**
     * Manually sends Text Messages.
     *
     * @param $objectId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendForContactAction($objectId)
    {
        $transportChain = $this->get('mautic.sms.transport_chain');
        if (!$transportChain->getEnabledTransports()) {
            return new JsonResponse(json_encode(['error' => ['message' => 'SMS transport is disabled.', 'code' => Response::HTTP_EXPECTATION_FAILED]]));
        }

        /** @var \Mautic\EmailBundle\Model\SmsModel $model */
        $model   = $this->getModel('lead');
        $lead  = $model->getEntity($objectId);

        if (!$this->get('mautic.security')->hasEntityAccess(
            'lead:leads:viewown',
            'lead:leads:viewother',
            $lead->getCreatedBy()
        )) {
            return $this->accessDenied();
        }
        $action   = $this->generateUrl('mautic_irsms_action', ['objectAction' => 'sendForContact', 'objectId' => $objectId]);
        $form     = $this->get('form.factory')->create(SendSmsToContact::class, $lead, ['action' => $action]);

        if ('POST' == $this->request->getMethod()) {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    $formData = $this->request->request->get('lead_quicksms');
                    $smsTemplateId = $formData['template'];
                    if (empty($smsTemplateId)) {
                        $form['template']->addError(
                            new FormError(
                                $this->get('translator')->trans('mautic.lead.email.body.required', [], 'validators')
                            )
                        );
                        return $this->ajaxAction(
                            [
                                'viewParameters'  => [
                                    'form'    => $form->createView(),
                                    'lead'   => $lead,
                                ],
                                'contentTemplate' => 'IrSmsBundle:Send:form.html.php',
                                'passthroughVars' => [
                                    'mauticContent' => 'smsSend',
                                    'route'         => $action,
                                    'lead'          => $lead,
                                ],
                            ]
                        );
                    } else {
                        $transports = $transportChain->getEnabledTransports();
                        if (count($transports) > 0) {
                            /** @var \Mautic\SmsBundle\Model\SmsModel $smsModel */
                            $smsModel   = $this->getModel('sms');
                            $sms  = $smsModel->getEntity($smsTemplateId);

                            $smsModel->sendSms($sms, $lead);

                            $route          = 'mautic_contact_action';
                            $viewParameters = [
                                'objectAction' => 'view',
                                'objectId'     => $objectId,
                            ];
                            $func = 'view';
                            return $this->postActionRedirect(
                                [
                                    'returnUrl'       => $this->generateUrl($route, $viewParameters),
                                    'viewParameters'  => $viewParameters,
                                    'contentTemplate' => 'MauticLeadBundle:Lead:' . $func,
                                    'passthroughVars' => [
                                        'mauticContent' => 'lead',
                                        'closeModal'    => 1,
                                    ],
                                ]
                            );
                        }
                    }
                }
            }
        } else {
            //process and send
            $contentTemplate = 'IrSmsBundle:Send:form.html.php';
            $viewParameters  = [
                'form'    => $form->createView(),
                'lead'   => $lead,
            ];
        }

        return $this->delegateView(
            [
                'viewParameters'  => $viewParameters,
                'contentTemplate' => $contentTemplate,
                'passthroughVars' => [
                    'mauticContent' => 'smsSend',
                    'route'         => $action,
                    'lead'          => $lead,
                ],
            ]
        );
    }
}
