<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
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
    private $bundles;

    /**
     * @param array $bundles Bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
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
                ->scalarNode('yandex_translate_api_key')->defaultNull()->end()
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
        $existingBundles = $this->bundles;

        $root = (new TreeBuilder('override'))->getRootNode();
        $root->useAttributeAsKey('bundle')
            ->validate()
                ->ifTrue(function (array $bundles) use ($existingBundles): bool {
                    foreach ($bundles as $bundle => $subjects) {
                        if (!isset($existingBundles[$bundle])) {
                            throw new \InvalidArgumentException(sprintf('Bundle "%s" does not exist.', $bundle));
                        }

                        $basePath = dirname((new \ReflectionClass($existingBundles[$bundle]))->getFileName());

                        foreach ($subjects as $subject) {
                            foreach ($subject['templates'] as $relativePath) {
                                $absolutePath = implode(DIRECTORY_SEPARATOR, [$basePath, $relativePath]);

                                if (!is_readable($absolutePath)) {
                                    throw new \InvalidArgumentException(
                                        sprintf('Template directory or file "%s" is not readable.', $absolutePath)
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
                        ->arrayNode('templates')->prototype('scalar')->cannotBeEmpty()->end()->end()
                        ->arrayNode('entities')
                            ->prototype('scalar')
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(function ($entity): bool {
                                        return !class_exists((string)$entity);
                                    })
                                    ->thenInvalid('Entity %s does not exist.');

        return $root;
    }
}
