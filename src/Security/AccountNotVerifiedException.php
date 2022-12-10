<?php
namespace App\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
class AccountNotVerifiedAuthenticationException extends AuthenticationException
{
    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}