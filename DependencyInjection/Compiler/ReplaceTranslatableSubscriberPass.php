<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Darvin\Utils\EventListener\TranslatableSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Replace translatable event subscriber compiler pass
 */
class ReplaceTranslatableSubscriberPass implements CompilerPassInterface
{
    private const ID = 'knp.doctrine_behaviors.translatable_subscriber';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(self::ID)) {
            $container->getDefinition(self::ID)
                ->setClass(TranslatableSubscriber::class)
                ->addArgument(new Reference('darvin_utils.orm.entity_resolver'));
        }
    }
}