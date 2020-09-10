<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
     * @var array
     */
    private $bundlesMeta;

    /**
     * @param array $bundlesMeta Bundles metadata
     */
    public function __construct(array $bundlesMeta)
    {
        $this->bundlesMeta = $bundlesMeta;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('darvin_utils');

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $root */
        $root = $builder->getRootNode();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $root
            ->children()
                ->append($this->buildOverrideNode())
                ->scalarNode('tmp_dir')->defaultValue('%kernel.project_dir%/var/tmp/darvin/utils')->cannotBeEmpty()->end()
                ->arrayNode('cache')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('http')->canBeEnabled()
                            ->children()
                                ->scalarNode('dir')->defaultValue('%kernel.cache_dir%/http_cache')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                        ->arrayNode('varnish')->canBeEnabled()
                            ->children()
                                ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('method')->defaultValue('XCGFULLBAN')->cannotBeEmpty()->end()
                                ->integerNode('timeout')->defaultValue(3)->min(1)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('response')->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('compress')->defaultFalse();

        return $builder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    private function buildOverrideNode(): ArrayNodeDefinition
    {
        $bundlesMeta = $this->bundlesMeta;

        $root = (new TreeBuilder('override'))->getRootNode();
        $root->useAttributeAsKey('bundle')
            ->validate()
                ->ifTrue(function (array $bundles) use ($bundlesMeta): bool {
                    foreach ($bundles as $bundle => $subjects) {
                        if (!isset($bundlesMeta[$bundle])) {
                            throw new \InvalidArgumentException(sprintf('Bundle "%s" does not exist.', $bundle));
                        }

                        $baseNamespace = $bundlesMeta[$bundle]['namespace'];
                        $basePath      = sprintf('%s/Resources/views', $bundlesMeta[$bundle]['path']);

                        foreach ($subjects as $subject) {
                            foreach ($subject['entities'] as $entity) {
                                $class = sprintf('%s\Entity\%s', $baseNamespace, $entity);

                                if (!class_exists($class)) {
                                    throw new \InvalidArgumentException(sprintf('Entity class "%s" does not exist.', $class));
                                }
                            }
                            foreach ($subject['templates'] as $relativePath) {
                                $absolutePath = implode(DIRECTORY_SEPARATOR, [$basePath, $relativePath]);

                                if (!is_readable($absolutePath)) {
                                    throw new \InvalidArgumentException(
                                        sprintf('Template file or directory "%s" is not readable.', $absolutePath)
                                    );
                                }
                            }
                        }
                    }

                    return false;
                })
                ->thenInvalid('')
            ->end()
            ->prototype('array')->useAttributeAsKey('subject')
                ->prototype('array')
                    ->children()
                        ->arrayNode('entities')
                            ->prototype('scalar')->cannotBeEmpty()->end()
                            ->beforeNormalization()->castToArray()->end()
                        ->end()
                        ->arrayNode('templates')
                            ->prototype('scalar')->cannotBeEmpty()->end()
                            ->beforeNormalization()->castToArray();

        return $root;
    }
}
