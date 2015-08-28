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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Sluggable listener compiler pass
 */
class SluggableListenerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $listenerId = 'stof_doctrine_extensions.listener.sluggable';

        if (!$container->hasDefinition($listenerId)) {
            return;
        }

        $container->getDefinition($listenerId)->addMethodCall('setTransliterator', array(
            array(new Reference('darvin_utils.sluggable.transliterator'), 'transliterate'),
        ));
    }
}
