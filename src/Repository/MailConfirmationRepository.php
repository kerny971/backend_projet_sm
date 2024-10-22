<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use App\Document\MailConfirmation;

class MailConfirmationRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        parent::__construct($dm, $dm->getUnitOfWork(), $dm->getClassMetadata(MailConfirmation::class));
    }

    /**
     * @throws MongoDBException
     */
    public function findLastThreeEntries(string $user_id, \DateTime $dateTime, int $limit): int
    {
        return $this->createQueryBuilder()
            ->field('user')->equals($user_id)
            ->field('createdAt')->gt($dateTime->getTimestamp())
            ->sort('timestamp', 'desc')
            ->limit($limit)
            ->count()
            ->getQuery()
            ->execute();
    }
}


?>