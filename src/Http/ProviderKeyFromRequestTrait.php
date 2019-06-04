<?php

declare(strict_types=1);

/*
 * AJGL Logout Redirector Bundle
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Bundle\LogoutRedirectorBundle\Http;

use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;

trait ProviderKeyFromRequestTrait
{
    private $firewallMap;

    /**
     * @required
     */
    public function setFirewallMap(FirewallMap $firewallMap): void
    {
        $this->firewallMap = $firewallMap;
    }

    private function getProviderKeyFromRequest(Request $request): ?string
    {
        if (null === $this->firewallMap) {
            return null;
        }

        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        if (null === $firewallConfig) {
            return null;
        }

        return $firewallConfig->getName();
    }
}
