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

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Darvin\Utils\DependencyInjection\ConfigLoader;
use Darvin\Utils\DependencyInjection\ExtensionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DarvinUtilsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        (new ConfigInjector($container))->inject($config, $this->getAlias());

        (new ConfigLoader($container, __DIR__.'/../Resources/config'))->load([
            'anti_spam',
            'cloner',
            'custom_object',
            'default_value',
            'flash',
            'form',
            'homepage',
            'intl',
            'locale',
            'mapping',
            'new_object',
            'object_namer',
            'orm',
            'price',
            'routing',
            'security',
            'service',
            'sluggable',
            'stringifier',
            'transliteratable',
            'user',

            'tree'             => ['bundle' => 'StofDoctrineExtensionsBundle'],

            'dev/translation'  => ['env' => 'dev'],

            'response/compress' => ['callback' => function () use ($config) {
                return $config['response']['compress'];
            }],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        (new ExtensionConfigurator($container, __DIR__.'/../Resources/config/app'))->configure([
            'doctrine',
            'stof_doctrine_extensions',
        ]);
    }
}
