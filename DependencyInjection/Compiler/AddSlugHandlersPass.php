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

use Darvin\Utils\DependencyInjection\TaggedServiceIdsSorter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add slug handlers compiler pass
 */
class AddSlugHandlersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $ids = $container->findTaggedServiceIds('darvin_utils.slug_handler');

        (new TaggedServiceIdsSorter())->sort($ids);

        $manager = $container->getDefinition('darvin_utils.sluggable.manager.entity');

        foreach (array_keys($ids) as $id) {
            $manager->addMethodCall('addSlugHandler', [new Reference($id)]);
        }
    }
}
