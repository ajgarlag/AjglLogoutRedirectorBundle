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

use Ajgl\Bundle\LogoutRedirectorBundle\Http\ProviderKeyFromRequestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use function Safe\substr;

class LogoutRedirector
{
    use ProviderKeyFromRequestTrait;
    use TargetPathTrait;

    public const KEY_PREFIX = 'logout_';

    protected $httpUtils;
    protected $options;
    protected $providerKey;
    protected $defaultOptions = [
        'default_target_path' => '/',
        'logout_path' => '/logout',
        'target_path_parameter' => '_target_path',
        'use_referer' => false,
    ];

    public function __construct(HttpUtils $httpUtils, array $options = [])
    {
        $this->httpUtils = $httpUtils;
        $this->setOptions($options);
    }

    public function getLogoutRedirect(Request $request): Response
    {
        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = array_merge($this->defaultOptions, $options);
    }

    public function getProviderKey(): ?string
    {
        return $this->providerKey;
    }

    public function setProviderKey($providerKey): void
    {
        $this->providerKey = $providerKey;
    }

    protected function determineTargetUrl(Request $request): string
    {
        if ($targetUrl = ParameterBagUtils::getRequestParameterValue($request, $this->options['target_path_parameter'])) {
            return $targetUrl;
        }

        $providerKey = $this->providerKey ?? $this->getProviderKeyFromRequest($request);

        if (null !== $providerKey && $request->hasSession() && $targetUrl = $this->getTargetPath($request->getSession(), self::KEY_PREFIX.$providerKey)) {
            $this->removeTargetPath($request->getSession(), self::KEY_PREFIX.$providerKey);

            return $targetUrl;
        }

        if ($this->options['use_referer'] && $targetUrl = $request->headers->get('Referer')) {
            if (false !== $pos = strpos($targetUrl, '?')) {
                $targetUrl = substr($targetUrl, 0, $pos);
            }
            if ($targetUrl && $targetUrl !== $this->httpUtils->generateUri($request, $this->options['logout_path'])) {
                return $targetUrl;
            }
        }

        return $this->options['default_target_path'];
    }
}
