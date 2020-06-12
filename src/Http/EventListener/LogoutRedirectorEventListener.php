<?php

declare(strict_types=1);

/*
 * AJGL Logout Redirector Bundle
 *
 * Copyright (c) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Bundle\LogoutRedirectorBundle\Http\EventListener;

use Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout\LogoutRedirector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutRedirectorEventListener implements EventSubscriberInterface
{
    private $logoutRedirector;

    public function __construct(LogoutRedirector $logoutRedirector)
    {
        $this->logoutRedirector = $logoutRedirector;
    }

    public function onLogout(LogoutEvent $event): void
    {
        if ($event->getResponse() instanceof Response) {
            return;
        }

        $event->setResponse($this->logoutRedirector->getLogoutRedirect($event->getRequest()));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => ['onLogout', 128],
        ];
    }
}
