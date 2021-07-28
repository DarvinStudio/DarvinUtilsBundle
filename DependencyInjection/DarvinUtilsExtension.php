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

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Darvin\Utils\DependencyInjection\ConfigLoader;
use Darvin\Utils\DependencyInjection\ExtensionConfigurator;
use Darvin\Utils\Mapping\AnnotationDriver\AnnotationDriverInterface;
use Darvin\Utils\Sluggable\SlugHandlerInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
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

    private const BUNDLE_ADMIN               = 'DarvinAdminBundle';
    private const BUNDLE_CONTENT             = 'DarvinContentBundle';
    private const BUNDLE_DOCTRINE_EXTENSIONS = 'StofDoctrineExtensionsBundle';
    private const BUNDLE_SECURITY            = 'SecurityBundle';
    private const BUNDLE_TWIG                = 'TwigBundle';
    private const BUNDLE_UPLOADER            = 'VichUploaderBundle';

    private const CLASS_FILESYSTEM      = 'Symfony\Component\Filesystem\Filesystem';
    private const CLASS_FORM            = 'Symfony\Component\Form\Form';
    private const CLASS_PROPERTY_ACCESS = 'Symfony\Component\PropertyAccess\PropertyAccessor';
    private const CLASS_TRANSLATION     = 'Symfony\Component\Translation\Translator';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(AnnotationDriverInterface::class)->addTag(self::TAG_ANNOTATION_DRIVER);
        $container->registerForAutoconfiguration(SlugHandlerInterface::class)->addTag(self::TAG_SLUG_HANDLER);

        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        (new ConfigInjector($container))->inject($config, $this->getAlias());

        (new ConfigLoader($container, __DIR__.'/../Resources/config/services'))->load([
            'callback',
            'data',
            'flash',
            'homepage',
            'json',
            'locale',
            'mapping',
            'new_object',
            'object_namer/common',
            'orm',
            'routing',
            'service',
            'transliteratable/common',

            'cache/http' => ['callback' => function () use ($config): bool {
                return $config['cache']['http']['enabled'];
            }],

            'cache/varnish' => ['extension' => 'curl', 'callback' => function () use ($config): bool {
                return $config['cache']['varnish']['enabled'];
            }],

            'cloner/common'     => ['class' => self::CLASS_PROPERTY_ACCESS],
            'cloner/uploadable' => ['class' => [self::CLASS_PROPERTY_ACCESS, self::CLASS_FILESYSTEM], 'bundle' => self::BUNDLE_UPLOADER],

            'custom_object' => ['class' => self::CLASS_PROPERTY_ACCESS],

            'default_value' => ['class' => self::CLASS_PROPERTY_ACCESS],

            'dev/override' => [
                'env'    => 'dev',
                'class'  => self::CLASS_FILESYSTEM,
                'bundle' => [self::BUNDLE_ADMIN, self::BUNDLE_CONTENT, self::BUNDLE_TWIG],
            ],

            'dev/translation' => ['env' => 'dev'],

            'form' => ['class' => self::CLASS_FORM],

            'object_namer/twig' => ['bundle' => self::BUNDLE_TWIG],

            'price' => ['bundle' => self::BUNDLE_TWIG],

            'response/compress' => ['callback' => function () use ($config) {
                return $config['response']['compress'];
            }],

            'security' => ['bundle' => self::BUNDLE_SECURITY],

            'sluggable' => ['class' => self::CLASS_PROPERTY_ACCESS],

            'stringifier' => ['class' => self::CLASS_TRANSLATION],

            'transliteratable/subscriber' => ['class' => self::CLASS_PROPERTY_ACCESS],

            'tree' => ['bundle' => self::BUNDLE_DOCTRINE_EXTENSIONS, 'class' => self::CLASS_PROPERTY_ACCESS],

            'user' => ['bundle' => self::BUNDLE_SECURITY],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        (new ExtensionConfigurator($container, __DIR__.'/../Resources/config/app'))->configure([
            'doctrine',
            'stof_doctrine_extensions',
            'twig',
        ]);

        if ($container->hasExtension('framework')) {
            $container->prependExtensionConfig('framework', [
                'translator' => [
                    'paths' => [
                        sprintf('%s/translations', sprintf('%s/../Resources', dirname((new \ReflectionClass(ExtensionConfigurator::class))->getFileName()))),
                    ],
                ],
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration($container->getParameter('kernel.bundles_metadata'));
    }
}
