<?php 

namespace App\Functions;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CheckUser {

    private Object $user;

    public function __construct(array $payload, UserRepository $userRepo) {
        $this->user = $userRepo->find($payload['id']);
    }

    public function check(): array {

        if (!$this->user->isConfirmed()) {
            return [
                'message' => "Veuillez confirmer votre inscription",
                'status_code' => 403,
                'error_code' => "ACCESS_DENIED_NOT_CONFIRMED"
            ];
        }

        if ($this->user->isBanned()) {
            return [
                'message' => "Vous n\'êtes plus autorisée à accéder à cette ressource",
                'status_code' => 403,
                'error_code' => "ACCESS_DENIED_BAN"
            ];
        }

        return [];
    }

    public function getUser(): User
    {
        return $this->user;
    }

}

?>