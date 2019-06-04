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

namespace Ajgl\Bundle\LogoutRedirectorBundle\Tests\Http\Logout;

use Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout\LogoutSuccessHandler;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class LogoutSuccessHandlerTest extends TestCase
{
    /**
     * @dataProvider getRequestRedirections
     */
    public function testRequestRedirections(Request $request, $options, $redirectedUrl): void
    {
        $urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $urlGenerator->expects($this->any())->method('generate')->willReturn('http://localhost/logout');
        $httpUtils = new HttpUtils($urlGenerator);
        $handler = new LogoutSuccessHandler($httpUtils, $options);
        if ($request->hasSession()) {
            $handler->setProviderKey('admin');
        }
        $this->assertSame('http://localhost'.$redirectedUrl, $handler->onLogoutSuccess($request)->getTargetUrl());
    }

    public function getRequestRedirections()
    {
        $session = $this->getMockBuilder(SessionInterface::class)->getMock();
        $session->expects($this->once())->method('get')->with('_security.'.LogoutSuccessHandler::KEY_PREFIX.'admin.target_path')->willReturn('/admin/goodbye');
        $session->expects($this->once())->method('remove')->with('_security.'.LogoutSuccessHandler::KEY_PREFIX.'admin.target_path');
        $requestWithSession = Request::create('/');
        $requestWithSession->setSession($session);

        return [
            'default' => [
                Request::create('/'),
                [],
                '/',
            ],
            'target path as query string' => [
                Request::create('/?_target_path=/goodbye'),
                [],
                '/goodbye',
            ],
            'target path name as query string is customized' => [
                Request::create('/?_my_target_path=/goodbye'),
                ['target_path_parameter' => '_my_target_path'],
                '/goodbye',
            ],
            'target path name as query string is customized and nested' => [
                Request::create('/?_target_path[value]=/goodbye'),
                ['target_path_parameter' => '_target_path[value]'],
                '/goodbye',
            ],
            'target path in session' => [
                $requestWithSession,
                [],
                '/admin/goodbye',
            ],
            'target path as referer' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/goodbye']),
                ['use_referer' => true],
                '/goodbye',
            ],
            'target path as referer is ignored if not configured' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/goodbye']),
                [],
                '/',
            ],
            'target path as referer when referer not set' => [
                Request::create('/'),
                ['use_referer' => true],
                '/',
            ],
            'target path as referer when referer is ?' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => '?']),
                ['use_referer' => true],
                '/',
            ],
            'target path should be different than logout URL' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/logout']),
                ['use_referer' => true, 'logout_path' => '/logout'],
                '/',
            ],
            'target path should be different than logout URL (query string does not matter)' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/logout?t=1&p=2']),
                ['use_referer' => true, 'logout_path' => '/logout'],
                '/',
            ],
            'target path should be different than logout URL (logout_path as a route)' => [
                Request::create('/', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/logout?t=1&p=2']),
                ['use_referer' => true, 'logout_path' => 'logout_route'],
                '/',
            ],
        ];
    }
}
