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

namespace Ajgl\Bundle\LogoutRedirectorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AjglLogoutRedirectorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->configureLogoutRedirector($container, $config['redirectors']);
    }

    private function configureLogoutRedirector(ContainerBuilder $container, array $redirectors): void
    {
        foreach ($redirectors as $context => $config) {
            $redirectorChildDefinition = new ChildDefinition('ajgl_logout_redirector.security.logout.redirector');
            $redirectorChildDefinition->replaceArgument(1, $context);
            $redirectorChildDefinition->replaceArgument(2, $config);
            $redirectorChildDefinition->addTag('ajgl_logout_redirector', ['context' => $context]);
            $container->setDefinition('ajgl_logout_redirector.security.logout.redirector.'.$context, $redirectorChildDefinition);
        }
    }
}
