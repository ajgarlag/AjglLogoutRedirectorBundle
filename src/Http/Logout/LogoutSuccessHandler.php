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

namespace Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    protected $logoutRedirector;

    public function __construct(LogoutRedirector $logoutRedirector)
    {
        $this->logoutRedirector = $logoutRedirector;
    }

    public function onLogoutSuccess(Request $request): Response
    {
        return $this->logoutRedirector->getLogoutRedirect($request);
    }
}
