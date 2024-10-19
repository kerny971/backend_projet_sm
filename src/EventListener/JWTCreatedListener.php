<?php

    namespace App\EventListener;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

    class JWTCreatedListener
    {

        private $userRepository;

        public function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }


        public function onJWTCreated(JWTCreatedEvent $event)
        {
            $payload = $event->getData();

            // Re-récupérer l'utilisateur en base pour réajouté ses données dans le payload
            $user = $this->userRepository->findOneBy(['email' => $payload['username']]);

            // Add custom data to the token payload4
            $payload['id'] = $user->getId();
            $payload['pseudo'] = $user->getPseudo();
            $payload['firstname'] = $user->getFirstname();
            $payload['lastname'] = $user->getLastname();
            $payload['created_at'] = $user->getCreatedAt()->getTimestamp();
            $payload['updated_at'] = $user->getUpdatedAt()->getTimeStamp();
            $payload['is_confirmed'] = $user->IsConfirmed();
            $payload['is_banned'] = $user->IsBanned();

            $event->setData($payload);
        }
    }

?>