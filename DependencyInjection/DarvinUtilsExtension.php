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
use Darvin\Utils\Mapping\AnnotationDriver\AnnotationDriverInterface;
use Darvin\Utils\Sluggable\SlugHandlerInterface;
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
    public const TAG_ANNOTATION_DRIVER = 'darvin_utils.annotation_driver';
    public const TAG_SLUG_HANDLER      = 'darvin_utils.slug_handler';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(AnnotationDriverInterface::class)->addTag(self::TAG_ANNOTATION_DRIVER);
        $container->registerForAutoconfiguration(SlugHandlerInterface::class)->addTag(self::TAG_SLUG_HANDLER);

        $config = $this->processConfiguration(new Configuration(), $configs);

        (new ConfigInjector($container))->inject($config, $this->getAlias());

        (new ConfigLoader($container, __DIR__.'/../Resources/config/services'))->load([
            'cloner',
            'custom_object',
            'default_value',
            'flash',
            'homepage',
            'locale',
            'mapping',
            'new_object',
            'object_namer',
            'orm',
            'price',
            'routing',
            'service',
            'sluggable',
            'stringifier',
            'transliteratable',

            'dev/translation' => ['env' => 'dev'],

            'form' => ['callback' => function () use ($config) {
                return $config['form']['enabled'];
            }],

            'response/compress' => ['callback' => function () use ($config) {
                return $config['response']['compress'];
            }],

            'security' => ['callback' => function () use ($config) {
                return $config['security']['enabled'];
            }],

            'tree' => ['bundle' => 'StofDoctrineExtensionsBundle'],

            'user' => ['callback' => function () use ($config) {
                return $config['user']['enabled'];
            }],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $securityCore = interface_exists('Symfony\Component\Security\Core\User\UserInterface');

        if (!class_exists('Symfony\Component\Form\Form')) {
            $container->prependExtensionConfig($this->getAlias(), [
                'form' => [
                    'enabled' => false,
                ],
            ]);
        }
        if (!$securityCore) {
            $container->prependExtensionConfig($this->getAlias(), [
                'security' => [
                    'enabled' => false,
                ],
            ]);
        }
        if (!$securityCore) {
            $container->prependExtensionConfig($this->getAlias(), [
                'user' => [
                    'enabled' => false,
                ],
            ]);
        }

        (new ExtensionConfigurator($container, __DIR__.'/../Resources/config/app'))->configure([
            'doctrine',
            'stof_doctrine_extensions',
        ]);
    }
}
