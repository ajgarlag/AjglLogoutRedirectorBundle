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
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Security\Http\Event\LogoutEvent;

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
        foreach ($redirectors as $name => $config) {
            $redirectorChildDefinition = new ChildDefinition('ajgl_logout_redirector.security.logout.redirector');
            $redirectorChildDefinition->replaceArgument(1, $config);
            $container->setDefinition('ajgl_logout_redirector.security.logout.redirector.'.$name, $redirectorChildDefinition);

            if (class_exists(LogoutEvent::class)) {
                $listenerChildDefinition = new ChildDefinition('ajgl_logout_redirector.security.logout.listener');
                $listenerChildDefinition->replaceArgument(0, new Reference('ajgl_logout_redirector.security.logout.redirector.'.$name));
                $listenerChildDefinition->addTag('kernel.event_subscriber');
                $container->setDefinition('ajgl_logout_redirector.security.logout.listener.'.$name, $listenerChildDefinition);
            } else {
                $handlerChildDefinition = new ChildDefinition('ajgl_logout_redirector.security.logout.success_handler');
                $handlerChildDefinition->replaceArgument(0, new Reference('ajgl_logout_redirector.security.logout.redirector.'.$name));
                $container->setDefinition('ajgl_logout_redirector.security.logout.success_handler.'.$name, $handlerChildDefinition);
            }
        }
    }
}
