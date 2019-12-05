<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2018, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Override entities compiler pass
 */
class OverrideEntitiesPass implements CompilerPassInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $bundles         = $container->getParameter('kernel.bundles_metadata');
        $implementations = $replacements = [];

        foreach ($bundles as $bundle => $attr) {
            if (0 !== strpos($attr['namespace'], 'Darvin\\')) {
                continue;
            }

            $dir = implode(DIRECTORY_SEPARATOR, [$attr['path'], 'Entity']);

            if (!$this->filesystem->exists($dir)) {
                continue;
            }
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ((new Finder())->in($dir)->files()->name('*.php') as $file) {
                $class = str_replace(DIRECTORY_SEPARATOR, '\\', preg_replace('/.php$/', '', $file->getRelativePathname()));

                $fqcn = implode('\\Entity\\', [$attr['namespace'], $class]);

                if (!class_exists($fqcn)) {
                    continue;
                }

                $parts   = array_merge(['App', 'Entity', preg_replace('/^Darvin|Bundle$/', '', $bundle)], explode('\\', $class));
                $parts[] = sprintf('App%s', array_pop($parts));

                $replacement = implode('\\', $parts);

                if (class_exists($replacement) && in_array($fqcn, class_parents($replacement))) {
                    $replacements[$fqcn] = $replacement;
                }
                foreach (class_implements($fqcn) as $interface) {
                    if (sprintf('%sInterface', $fqcn) === $interface) {
                        $implementations[$interface] = $replacements[$interface] = $replacements[$fqcn] ?? $fqcn;

                        break;
                    }
                }
            }
        }
        if (!empty($implementations)) {
            $listener = $container->getDefinition('doctrine.orm.listeners.resolve_target_entity');

            foreach ($implementations as $interface => $implementation) {
                $listener->addMethodCall('addResolveTargetEntity', [$interface, $implementation, []]);
            }
        }
        if (!empty($replacements)) {
            $resolver = $container->getDefinition('darvin_utils.orm.entity_resolver');

            foreach ($replacements as $entity => $replacement) {
                $resolver->addMethodCall('addReplacement', [$entity, $replacement]);
            }
        }
    }
}
