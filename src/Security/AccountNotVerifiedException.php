<?php
namespace App\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
class AccountNotVerifiedException extends AuthenticationException
{
    public static function __callStatic(string $name, array $arguments)
    {
        // TODO: Implement __callStatic() method.
    }
}