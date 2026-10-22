<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) 2026, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\Tests\Resources;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Doctrine prepended configuration test
 */
class DoctrineConfigTest extends TestCase
{
    public function testResolveTargetEntitiesHasOnlyStringKeys(): void
    {
        $config = Yaml::parseFile(__DIR__.'/../../Resources/config/app/doctrine.yaml');

        $targets = $config['doctrine']['orm']['resolve_target_entities'];

        self::assertIsArray($targets);

        foreach (array_keys($targets) as $key) {
            self::assertIsString($key, 'resolve_target_entities keys must be strings (integer keys break ORM on PHP 8).');
        }
    }
}
