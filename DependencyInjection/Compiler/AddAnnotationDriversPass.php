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
 * Add annotation drivers compiler pass
 */
class AddAnnotationDriversPass implements CompilerPassInterface
{
    const TAG_DRIVER = 'darvin_utils.annotation_driver';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $driverIds = $container->findTaggedServiceIds(self::TAG_DRIVER);

        if (empty($driverIds)) {
            return;
        }

        $factoryDefinition = $container->getDefinition('darvin_utils.mapping.metadata_factory');

        foreach ($driverIds as $id => $attr) {
            $factoryDefinition->addMethodCall('addAnnotationDriver', array(
                new Reference($id),
            ));
        }
    }
}
