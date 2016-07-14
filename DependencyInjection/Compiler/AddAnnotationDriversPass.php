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
    const METADATA_FACTORY_ID = 'darvin_utils.mapping.metadata_factory';

    const TAG_ANNOTATION_DRIVER = 'darvin_utils.annotation_driver';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::METADATA_FACTORY_ID)) {
            return;
        }

        $driverIds = $container->findTaggedServiceIds(self::TAG_ANNOTATION_DRIVER);

        if (empty($driverIds)) {
            return;
        }

        $factoryDefinition = $container->getDefinition(self::METADATA_FACTORY_ID);

        foreach ($driverIds as $id => $attr) {
            $factoryDefinition->addMethodCall('addAnnotationDriver', [
                new Reference($id),
            ]);
        }
    }
}
