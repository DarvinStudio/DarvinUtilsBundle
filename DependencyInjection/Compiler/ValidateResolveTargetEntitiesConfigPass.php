<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Darvin\Utils\Exception\ConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Validate resolve target entities configuration compiler pass
 */
class ValidateResolveTargetEntitiesConfigPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getExtensionConfig('doctrine') as $part) {
            if (!isset($part['orm']['resolve_target_entities'])) {
                continue;
            }
            foreach ($part['orm']['resolve_target_entities'] as $target => $replacement) {
                if (!interface_exists($target) && !class_exists($target)) {
                    throw new ConfigurationException(sprintf('Target interface/class "%s" does not exist.', $target));
                }
                if (!class_exists($replacement)) {
                    throw new ConfigurationException(sprintf('Replacement entity class "%s" does not exist.', $replacement));
                }
                if (!in_array($target, array_merge(class_implements($replacement), class_parents($replacement)))) {
                    throw new ConfigurationException(sprintf(
                        'Replacement entity class "%s" must implement/extend target interface/class "%s".', 
                        $replacement, 
                        $target
                    ));
                }
            }
        }
    }
}
