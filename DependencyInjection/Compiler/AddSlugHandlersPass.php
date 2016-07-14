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
    const SLUGGABLE_MANAGER_ID = 'darvin_utils.sluggable.manager.entity';

    const TAG_SLUG_HANDLER = 'darvin_utils.slug_handler';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SLUGGABLE_MANAGER_ID)) {
            return;
        }

        $handlerIds = $container->findTaggedServiceIds(self::TAG_SLUG_HANDLER);

        if (empty($handlerIds)) {
            return;
        }

        $sorter = new TaggedServiceIdsSorter();
        $sorter->sort($handlerIds);

        $sluggableManagerDefinition = $container->getDefinition(self::SLUGGABLE_MANAGER_ID);

        foreach ($handlerIds as $id => $attr) {
            $sluggableManagerDefinition->addMethodCall('addSlugHandler', [
                new Reference($id),
            ]);
        }
    }
}
