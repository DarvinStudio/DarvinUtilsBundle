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
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * Create service providers compiler pass
 */
class CreateServiceProvidersPass implements CompilerPassInterface
{
    const ID_SUFFIX = '.provider';

    const PARENT_ID = 'darvin_utils.service.abstract_provider';

    const TAG_PROVIDABLE = 'darvin_utils.providable';

    /**
     * @var array
     */
    private static $ids = [
        'darvin_utils.authorization_checker.provider' => 'security.authorization_checker',
        'darvin_utils.entity_manager.provider'        => 'doctrine.orm.default_entity_manager',
        'darvin_utils.object_manager.provider'        => 'doctrine.orm.default_entity_manager',
        'darvin_utils.templating.provider'            => 'templating',
    ];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $ids = array_keys($container->findTaggedServiceIds(self::TAG_PROVIDABLE));
        $ids = array_merge(self::$ids, array_combine(array_map(function ($id) {
            return $id.self::ID_SUFFIX;
        }, $ids), $ids));

        $this->createServiceProviders($container, $ids);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container DI container
     * @param array                                                   $ids       Service IDs
     *
     * @throws \RuntimeException
     */
    public function createServiceProviders(ContainerBuilder $container, array $ids)
    {
        $definitions = [];

        foreach ($ids as $providerId => $id) {
            if ($container->hasDefinition($providerId)) {
                throw new \RuntimeException(
                    sprintf('Unable to create provider for service "%s": service "%s" already exists.', $id, $providerId)
                );
            }

            $definitions[$providerId] = (new DefinitionDecorator(self::PARENT_ID))->addArgument($id);
        }

        $container->addDefinitions($definitions);
    }
}
