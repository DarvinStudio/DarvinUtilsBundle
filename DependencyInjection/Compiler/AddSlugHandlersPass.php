<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Darvin\Utils\DependencyInjection\ServiceSorter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add slug handlers compiler pass
 */
class AddSlugHandlersPass implements CompilerPassInterface
{
    private const ID = 'darvin_utils.sluggable.manager';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(self::ID)) {
            return;
        }

        $manager = $container->getDefinition(self::ID);

        foreach (array_keys((new ServiceSorter())->sort($container->findTaggedServiceIds('darvin_utils.slug_handler'))) as $id) {
            $manager->addMethodCall('addSlugHandler', [new Reference($id)]);
        }
    }
}
