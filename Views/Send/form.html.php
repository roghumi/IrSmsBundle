<?php

if ($dnc && \Mautic\LeadBundle\Entity\DoNotContact::BOUNCED === $dnc->getReason()) {
    echo '<div class="alert alert-warning">'.$view['translator']->trans('mautic.lead.do.not.contact_bounced').': '.$dnc->getComments().'</div>';
} else {
    echo $view['form']->start($form);

    echo $view['form']->row($form['template']);

    echo $view['form']->end($form);
}
