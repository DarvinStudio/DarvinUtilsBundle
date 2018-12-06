<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2016, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection;

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

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
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $configInjector = new ConfigInjector();
        $configInjector->inject($config, $container, $this->getAlias());

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach ([
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
            'price',
            'routing',
            'security',
            'service',
            'sluggable',
            'stringifier',
            'transliteratable',
            'tree',
            'user',
        ] as $resource) {
            $loader->load($resource.'.yml');
        }
        if ('dev' === $container->getParameter('kernel.environment')) {
            foreach ([
                'translation',
            ] as $resource) {
                $loader->load(sprintf('dev/%s.yml', $resource));
            }
            if (interface_exists('Doctrine\Common\DataFixtures\FixtureInterface')) {
                $loader->load('dev/data_fixture.yml');
            }
        }
        if ($config['mailer']['enabled']) {
            $loader->load('mailer.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config/app');

        foreach ([
            'stof_doctrine_extensions',
        ] as $extension) {
            if ($container->hasExtension($extension)) {
                $container->prependExtensionConfig($extension, Yaml::parse(file_get_contents($fileLocator->locate($extension.'.yml')))[$extension]);
            }
        }
    }
}
