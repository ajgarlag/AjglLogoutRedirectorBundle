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

namespace Ajgl\Bundle\LogoutRedirectorBundle\Tests\Http\Logout;

use Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout\LogoutRedirector;
use Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout\LogoutSuccessHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LogoutSuccessHandlerTest extends TestCase
{
    public function testRequestRedirections(): void
    {
        $request = Request::create('/logout');
        $response = new RedirectResponse('http://localhost/after-logout');
        $logoutRedirector = $this->getMockBuilder(LogoutRedirector::class)->disableOriginalConstructor()->getMock();
        $logoutRedirector->expects($this->once())->method('getLogoutRedirect')->with($this->equalTo($request))->willReturn($response);
        $handler = new LogoutSuccessHandler($logoutRedirector);

        $logoutResponse = $handler->onLogoutSuccess($request);

        $this->assertSame('http://localhost/after-logout', $logoutResponse->getTargetUrl());
    }
}
