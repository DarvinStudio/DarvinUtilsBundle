<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2018, Darvin Studio
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
    public function load(array $configs, ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        $config  = $this->processConfiguration(new Configuration(), $configs);
        $loader  = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        (new ConfigInjector())->inject($config, $container, $this->getAlias());

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
            'orm',
            'price',
            'routing',
            'security',
            'service',
            'sluggable',
            'stringifier',
            'transliteratable',
            'user',
        ] as $resource) {
            $loader->load($resource.'.yaml');
        }
        if ('dev' === $container->getParameter('kernel.environment')) {
            foreach ([
                'translation',
            ] as $resource) {
                $loader->load(sprintf('dev/%s.yaml', $resource));
            }
            if (interface_exists('Doctrine\Common\DataFixtures\FixtureInterface')) {
                $loader->load('dev/data_fixture.yaml');
            }
        }
        if ($config['mailer']['enabled']) {
            if (!isset($bundles['SwiftmailerBundle'])) {
                throw new \RuntimeException(<<<MESSAGE
In order to use mailer please install Swiftmailer bundle:
$ composer require symfony/swiftmailer-bundle
MESSAGE
                );
            }

            $loader->load('mailer.yaml');
        }

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['StofDoctrineExtensionsBundle'])) {
            $loader->load('tree.yaml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config/app');

        foreach ([
            'doctrine',
            'stof_doctrine_extensions',
        ] as $extension) {
            if ($container->hasExtension($extension)) {
                $container->prependExtensionConfig($extension, Yaml::parse(file_get_contents($fileLocator->locate($extension.'.yaml')))[$extension]);
            }
        }
    }
}
