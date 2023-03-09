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
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutRedirectorEventListener implements EventSubscriberInterface
{
    private FirewallMap $firewallMap;
    /**
     * @var array<string, LogoutRedirector>
     */
    private array $logoutRedirectors;

    /**
     * @param iterable<string, LogoutRedirector> $logoutRedirectors
     */
    public function __construct(FirewallMap $firewallMap, iterable $logoutRedirectors)
    {
        $this->logoutRedirectors = $logoutRedirectors instanceof \Traversable ? iterator_to_array($logoutRedirectors) : $logoutRedirectors;
    }

    public function onLogout(LogoutEvent $event): void
    {
        if ($event->getResponse() instanceof Response) {
            return;
        }

        $request = $event->getRequest();
        $context = $this->getFirewallContext($request);

        if (null === $context || !array_key_exists($context, $this->logoutRedirectors)) {
            return;
        }

        $response = $this->logoutRedirectors[$context]->getLogoutRedirect($request);
        if ($response instanceof Response) {
            $event->setResponse($response);
        }
    }

    private function getFirewallContext(Request $request): ?string
    {
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        if (null === $firewallConfig) {
            return null;
        }

        return $firewallConfig->getContext();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => ['onLogout', 128],
        ];
    }
}
