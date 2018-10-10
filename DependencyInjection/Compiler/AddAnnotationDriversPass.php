<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2018, Darvin Studio
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
 * Add annotation drivers compiler pass
 */
class AddAnnotationDriversPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $factory = $container->getDefinition('darvin_utils.mapping.metadata_factory');

        foreach (array_keys($container->findTaggedServiceIds('darvin_utils.annotation_driver')) as $id) {
            $factory->addMethodCall('addAnnotationDriver', [new Reference($id)]);
        }
    }
}
