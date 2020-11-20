<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle;

use Darvin\UtilsBundle\DependencyInjection\Compiler\AddAnnotationDriversPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\AddOverridersPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\AddSlugHandlersPass;
//use Darvin\UtilsBundle\DependencyInjection\Compiler\AliasServicesPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\CreateServiceProvidersPass;
use Darvin\UtilsBundle\DependencyInjection\Compiler\OverrideEntitiesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Utils bundle
 */
class DarvinUtilsBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new AddAnnotationDriversPass())
            ->addCompilerPass(new AddOverridersPass())
            ->addCompilerPass(new AddSlugHandlersPass())
//            ->addCompilerPass(new AliasServicesPass())
            ->addCompilerPass(new CreateServiceProvidersPass())
            ->addCompilerPass(new OverrideEntitiesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }
}
