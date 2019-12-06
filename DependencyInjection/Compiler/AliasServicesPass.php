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

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Alias services compiler pass
 */
class AliasServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $aliases = $ambiguous = [];

        $addAlias = function (string $alias, string $id, Definition $definition) use (&$aliases, &$ambiguous, $container): void {
            if ($container->hasDefinition($alias) || $container->hasAlias($alias)) {
                return;
            }
            if (!isset($aliases[$alias])) {
                $aliases[$alias] = new Alias($id, $definition->isPublic());
            } else {
                $ambiguous[] = $alias;
            }
        };

        foreach ($container->getDefinitions() as $id => $definition) {
            if (0 !== strpos($id, 'darvin_')) {
                continue;
            }

            $class = $container->getParameterBag()->resolveValue($definition->getClass());

            if (null === $class || !class_exists($class) || 0 !== strpos($class, 'Darvin\\')) {
                continue;
            }

            $addAlias($class, $id, $definition);

            foreach (class_implements($class) as $interface) {
                if (0 === strpos($interface, 'Darvin\\')) {
                    $addAlias($interface, $id, $definition);
                }
            }
        }
        foreach ($ambiguous as $alias) {
            unset($aliases[$alias]);
        }

        $container->addAliases($aliases);
    }
}
