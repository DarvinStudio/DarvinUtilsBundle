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
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = [];

        foreach ($container->findTaggedServiceIds(self::TAG_PROVIDABLE) as $id => $tags) {
            foreach ($tags as $tag) {
                $providerId = isset($tag['id']) ? $tag['id'] : $id.self::ID_SUFFIX;

                if ($container->hasDefinition($providerId)) {
                    throw new \UnexpectedValueException(
                        sprintf('Unable to create provider for service "%s": service "%s" already exists.', $id, $providerId)
                    );
                }

                $definitions[$providerId] = (new DefinitionDecorator(self::PARENT_ID))->addArgument($id);
            }
        }

        $container->addDefinitions($definitions);
    }
}
