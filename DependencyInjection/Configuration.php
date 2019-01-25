<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2018, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('darvin_utils');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root */
        $root = $treeBuilder->getRootNode();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $root
            ->children()
                ->scalarNode('yandex_translate_api_key')->defaultNull()->end()
                ->arrayNode('mailer')->canBeEnabled()
                    ->children()
                        ->scalarNode('charset')->defaultValue('utf-8')->cannotBeEmpty()->end()
                        ->booleanNode('add_host')->defaultFalse()->end()
                        ->arrayNode('from')->isRequired()
                            ->children()
                                ->scalarNode('email')->isRequired()->end()
                                ->scalarNode('name')->defaultNull();

        return $treeBuilder;
    }
}
