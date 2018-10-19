<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle;

use Darvin\UtilsBundle\DependencyInjection\Compiler\AddAnnotationDriversPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\AddSlugHandlersPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\CreateServiceProvidersPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\OverrideEntitiesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Utils bundle
 */
class DarvinUtilsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new AddAnnotationDriversPass())
            ->addCompilerPass(new AddSlugHandlersPass())
            ->addCompilerPass(new CreateServiceProvidersPass())
            ->addCompilerPass(new OverrideEntitiesPass());
    }
}
