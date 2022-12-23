<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        dd($user);
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user->isVerified()) {
            throw new AccountNotVerifiedException("Please verify your account before logging in");
        }
    }
}