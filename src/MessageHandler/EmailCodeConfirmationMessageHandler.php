<?php

namespace App\MessageHandler;

use App\Document\MailConfirmation;
use App\DTO\ConfirmationEmailDTO;
use App\Entity\User;
use App\Event\EmailCodeConfirmationRequestEvent;
use App\Functions\Date as AppDate;
use App\Message\EmailCodeConfirmationMessage;
use App\Repository\MailConfirmationRepository;
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
        private readonly MailConfirmationRepository $mailConfirmationRepository
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

        # Verify code confirmation sent in the last hour
        try {
            $this->_checkNumberOfEmailCodeConfirmations(
                $user->getId(),
                $_ENV["DURATION_MAIL_CONFIRMATION_CODE_CHECK_1"],
                $_ENV["MAX_MAIL_CONFIRMATION_CODE_SENT_CHECK_1"]
            );
        }
        catch (\Exception $e) {
            throw new \Error($e->getMessage());
        }

        # Verify code confirmation sent in the last month
        try {
            $this->_checkNumberOfEmailCodeConfirmations(
                $user->getId(),
                $_ENV["DURATION_MAIL_CONFIRMATION_CODE_CHECK_2"],
                $_ENV["MAX_MAIL_CONFIRMATION_CODE_SENT_CHECK_2"]
            );
        }
        catch (\Exception $e) {
            throw new \Error($e->getMessage());
        }


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

    private function _checkNumberOfEmailCodeConfirmations (string $user_id, string $interval, int $max_sent): void
    {
        $clonedCurrentDate = clone $this->_currentDatetime;
        try {
            $clonedCurrentDate->modify($interval);
        } catch (\Exception) {
            throw new \Error("Error timestamp");
        }

        # Verify number code confirmation sent in the interval time
        try {
            $numberMailConfirmationsSent = $this->mailConfirmationRepository->findLastThreeEntries($user_id, $clonedCurrentDate, ($max_sent + 2));
        }
        catch (\Exception $e) {
            throw new \Error("DATABASE QUERIES CHECK CODE FAIL : " . $e->getMessage());
        }

        # check number of result
        if ($numberMailConfirmationsSent >= $max_sent) {
            throw new \Error("User reached Email Code Confirmation limit at interval " . $interval . " with maximum " . $max_sent . " mails");
        }
    }
}
