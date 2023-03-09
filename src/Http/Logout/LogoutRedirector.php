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

namespace Ajgl\Bundle\LogoutRedirectorBundle\Http\Logout;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LogoutRedirector
{
    use TargetPathTrait;

    public const KEY_PREFIX = 'logout_';

    protected $httpUtils;
    protected $context;
    protected $options;
    protected $providerKey;
    protected $defaultOptions = [
        'target_path_parameter' => '_target_path',
        'use_referer' => false,
        'redirect_uris' => [],
    ];

    public function __construct(HttpUtils $httpUtils, string $context, array $options = [])
    {
        $this->httpUtils = $httpUtils;
        $this->context = $context;
        $this->setOptions($options);
    }

    public function getLogoutRedirect(Request $request): ?Response
    {
        if (null === $targetUrl = $this->determineTargetUrl($request)) {
            return null;
        }

        foreach ($this->options['redirect_uris'] as $redirectUri) {
            if (str_starts_with($targetUrl, $redirectUri)) {
                return new RedirectResponse($targetUrl);
            }
        }

        return $this->httpUtils->createRedirectResponse($request, $targetUrl);
    }

    private function setOptions(array $options): void
    {
        $this->options = array_merge($this->defaultOptions, $options);
    }

    protected function determineTargetUrl(Request $request): ?string
    {
        if ($targetUrl = ParameterBagUtils::getRequestParameterValue($request, $this->options['target_path_parameter'])) {
            return $targetUrl;
        }

        if ($request->hasSession() && $targetUrl = $this->getTargetPath($request->getSession(), self::KEY_PREFIX.$this->context)) {
            $this->removeTargetPath($request->getSession(), self::KEY_PREFIX.$this->context);

            return $targetUrl;
        }

        if ($this->options['use_referer'] && null !== $targetUrl = $request->headers->get('Referer')) {
            return $targetUrl;
        }

        return null;
    }
}
