<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection;

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DarvinUtilsExtension extends Extension
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
        $loader->load('anti_spam.yml');
        $loader->load('cloner.yml');
        $loader->load('custom_object.yml');
        $loader->load('default_value.yml');
        $loader->load('flash.yml');
        $loader->load('mailer.yml');
        $loader->load('mapping.yml');
        $loader->load('new_object.yml');
        $loader->load('object_namer.yml');
        $loader->load('security.yml');
        $loader->load('slug.yml');
        $loader->load('stringifier.yml');
        $loader->load('templating.yml');
        $loader->load('transliteratable.yml');
    }
}
