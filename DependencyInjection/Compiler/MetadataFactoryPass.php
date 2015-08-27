<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Metadata factory compiler pass
 */
class MetadataFactoryPass implements CompilerPassInterface
{
    const TAG_ANNOTATION_DRIVER = 'darvin_utils.annotation_driver';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $factory = $container->getDefinition('darvin_utils.mapping.metadata_factory');

        foreach ($container->findTaggedServiceIds(self::TAG_ANNOTATION_DRIVER) as $id => $attr) {
            $factory->addMethodCall('addAnnotationDriver', array(
                new Reference($id),
            ));
        }
    }
}
