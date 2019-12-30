<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
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
 * Add overriders to pool compiler pass
 */
class AddOverridersPass implements CompilerPassInterface
{
    private const POOL = 'darvin_utils.override.overrider_pool';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(self::POOL)) {
            return;
        }

        $pool = $container->getDefinition(self::POOL);

        foreach (array_keys($container->findTaggedServiceIds('darvin_utils.overrider')) as $id) {
            $pool->addMethodCall('addOverrider', [new Reference($id)]);
        }
    }
}
