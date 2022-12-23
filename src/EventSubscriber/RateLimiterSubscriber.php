<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RateLimiterSubscriber implements EventSubscriberInterface {

    private RateLimiterFactory $anonymousAppLimiter;
    private RateLimiterFactory $authenticatedAppLimiter;
    private RateLimiterFactory $apiLimiter;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        RateLimiterFactory $anonymousAppLimiter,
        RateLimiterFactory $authenticatedAppLimiter,
        RateLimiterFactory $apiLimiter,
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->anonymousAppLimiter = $anonymousAppLimiter;
        $this->authenticatedAppLimiter = $authenticatedAppLimiter;
        $this->apiLimiter = $apiLimiter;
    }

    public static function getSubscribedEvents(): array {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void {
        $request = $event->getRequest();
        if(str_contains($request->get("_route"), 'app_')) {
            if ($this->tokenStorage->getToken()) {
                $limiter = $this->authenticatedAppLimiter->create($request->getClientIp());
                if (false === $limiter->consume(1)->isAccepted()) {
                    throw new TooManyRequestsHttpException();
                }
                return;
            }

            $limiter = $this->anonymousAppLimiter->create($request->getClientIp());
            if (false === $limiter->consume(1)->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }
            return;
        }

        if(str_contains($request->get("_route"), 'api_')) {
            $limiter = $this->apiLimiter->create($request->getClientIp());
            if (false === $limiter->consume(1)->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }
        }
    }
}