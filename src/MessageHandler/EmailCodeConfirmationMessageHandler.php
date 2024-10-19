<?php

namespace App\MessageHandler;

use App\Document\MailConfirmation;
use App\DTO\ConfirmationEmailDTO;
use App\Entity\User;
use App\Event\EmailCodeConfirmationRequestEvent;
use App\Functions\Date as AppDate;
use App\Message\EmailCodeConfirmationMessage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use PHLAK\StrGen\CharSet as CodeGeneratorCharset;
use PHLAK\StrGen\Generator as CodeGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EmailCodeConfirmationMessageHandler
{

    private \DateTime $_currentDatetime;

    public function __construct(
        private readonly DocumentManager $_documentManager,
        private readonly EventDispatcherInterface $_eventDispatcher,
        private readonly EntityManagerInterface $_entityManager,
    )
    {
        $this->_currentDatetime = AppDate::current();
    }

    public function __invoke(
        EmailCodeConfirmationMessage $message
    ): void
    {

        $user = $this->_entityManager->getRepository(User::class)->find($message->getUserId());

        if (!$user instanceof User) throw new \Error("User with id not found : " . $message->getUserId());

        # Generate Email code Confirmation
        $generatorCode = new CodeGenerator();
        $mailConfirmation = new MailConfirmation();

        try {
            $mailConfirmation->setCode($generatorCode->charset(CodeGeneratorCharset::ALPHA_NUMERIC)->length(10)->generate())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($this->_currentDatetime))
                ->setExpiredAt(\DateTimeImmutable::createFromMutable($this->_currentDatetime->modify($_ENV['DURATION_MAIL_CONFIRMATION_CODE'])))
                ->setUser($user->getId());
            $this->_documentManager->persist($mailConfirmation);
            $this->_documentManager->flush();
        }
        catch (\Exception|\Throwable $e) {
            throw new \Error($e->getMessage());
        }

        try {
            $this->_eventDispatcher->dispatch(
                new EmailCodeConfirmationRequestEvent(new ConfirmationEmailDTO($user, $mailConfirmation->getCode()))
            );
        }
        catch (\Exception $e) {
            throw new \Error($e->getMessage());
        }
    }
}
