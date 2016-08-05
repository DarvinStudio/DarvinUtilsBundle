<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Mark services providable compiler pass
 */
class MarkServicesProvidablePass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private static $ids = [
        'darvin_utils.authorization_checker.provider' => 'security.authorization_checker',
        'darvin_utils.entity_manager.provider'        => 'doctrine.orm.default_entity_manager',
        'darvin_utils.object_manager.provider'        => 'doctrine.orm.default_entity_manager',
    ];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach (self::$ids as $providerId => $id) {
            if ($container->hasDefinition($id)) {
                $container->getDefinition($id)->addTag(CreateServiceProvidersPass::TAG_PROVIDABLE, [
                    'id' => $providerId,
                ]);
            }
        }
    }
}
