<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2016-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Create service providers compiler pass
 */
class CreateServiceProvidersPass implements CompilerPassInterface
{
    private const ID_SUFFIX      = '.provider';
    private const PARENT_ID      = 'darvin_utils.service.abstract_provider';
    private const TAG_PROVIDABLE = 'darvin_utils.providable';

    private const IDS = [
        'darvin_utils.authorization_checker.provider' => 'security.authorization_checker',
        'darvin_utils.entity_manager.provider'        => 'doctrine.orm.default_entity_manager',
        'darvin_utils.object_manager.provider'        => 'doctrine.orm.default_entity_manager',
        'darvin_utils.templating.provider'            => 'templating',
    ];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $ids = self::IDS;

        foreach (array_keys($container->findTaggedServiceIds(self::TAG_PROVIDABLE)) as $id) {
            $ids[$id.self::ID_SUFFIX] = $id;
        }

        $definitions = [];

        foreach ($ids as $providerId => $id) {
            if ($container->hasDefinition($id) && !$container->getDefinition($id)->isPublic()) {
                throw new \RuntimeException(sprintf('Unable to create provider for service "%s": service is not public.', $id));
            }
            if ($container->hasDefinition($providerId)) {
                throw new \RuntimeException(
                    sprintf('Unable to create provider for service "%s": service "%s" already exists.', $id, $providerId)
                );
            }

            $definitions[$providerId] = (new ChildDefinition(self::PARENT_ID))->addArgument($id);
        }

        $container->addDefinitions($definitions);
    }
}
