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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ajgl_logout_redirector');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('redirectors')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('logout_path')->defaultValue('/logout')->end()
                            ->scalarNode('default_target_path')->defaultValue('/')->end()
                            ->scalarNode('target_path_parameter')->defaultValue('_target_path')->end()
                            ->booleanNode('use_referer')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
