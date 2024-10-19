<?php

namespace App\EventSubscriber;

use App\Event\EmailCodeConfirmationRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


class MailingSubscriber implements EventSubscriberInterface
{

    public function __construct (
        private MailerInterface $mailer
    ) {

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onEmailCodeConfirmationRequestEvent(EmailCodeConfirmationRequestEvent $event): void
    {
        $data = $event->getEmailConfirmationDTO();
        $fullname = $data->getLastName() . " " . $data->getFirstName();
        $mail = (new TemplatedEmail())
            ->from(new Address($_ENV['FROM_MAILER_NO_REPLY'], $_ENV['FROM_MAILER_NO_REPLY_NAME']))
            ->to(new Address($data->getEmail(), $fullname))
            ->replyTo(new Address($_ENV['FROM_MAILER_SUPPORT'], $_ENV['FROM_MAILER_SUPPORT_NAME']))
            ->subject("Confirmation de votre email")
            ->text("Merci de vous être inscrit ! Veuillez utiliser le code ci-dessous pour confirmer votre adresse email : " . $data->getCode())
            ->htmlTemplate("emails/confirmation-email.html.twig")
            ->context([
                'firstname' => $data->getFirstName(),
                'code' => $data->getCode(),
                'duration_code' => $_ENV['DURATION_MAIL_CONFIRMATION_CODE'],
                'url_app' => $_ENV['URL_APP']
            ]);

        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailCodeConfirmationRequestEvent::class => 'onEmailCodeConfirmationRequestEvent',
        ];
    }
}
