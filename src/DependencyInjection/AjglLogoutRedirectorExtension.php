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

        $this->configureLogoutSuccessHandlerDecorators($container, $config['handlers']);
    }

    private function configureLogoutSuccessHandlerDecorators(ContainerBuilder $container, array $handlers): void
    {
        foreach ($handlers as $name => $config) {
            $childDefinition = new ChildDefinition('ajgl_logout_redirector.security.logout.success_handler');
            $childDefinition->setArgument(2, $config);
            $container->setDefinition('ajgl_logout_redirector.security.logout.success_handler.'.$name, $childDefinition);
        }
    }
}
