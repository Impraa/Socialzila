<?php

namespace App\Security;

use DateTime;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;


class UserChecker implements UserCheckerInterface
{
    /** 
     * @param User $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user->getBannedUntil() === null) {
            return;
        }
        $now = new DateTime();

        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedException("User is banned");
        }
    }

    /** 
     * @param User $user
     */
    public function checkPostAuth(UserInterface $user): void
    {
    }
}
