<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2018, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UtilsBundle\Price;

use Darvin\Utils\Price\PriceFormatterInterface;
use Darvin\Utils\Service\ServiceProviderInterface;

/**
 * Price formatter
 */
class PriceFormatter implements PriceFormatterInterface
{
    /**
     * @var \Darvin\Utils\Service\ServiceProviderInterface
     */
    private $templatingProvider;

    /**
     * @param \Darvin\Utils\Service\ServiceProviderInterface $templatingProvider Templating service provider
     */
    public function __construct(ServiceProviderInterface $templatingProvider)
    {
        $this->templatingProvider = $templatingProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function format($price, array $options = [])
    {
        return $this->getTemplating()->render(isset($options['template']) ? $options['template'] : '@DarvinUtils/price.html.twig', [
            'price'   => $price,
            'options' => $options,
        ]);
    }

    /**
     * @return \Symfony\Component\Templating\EngineInterface
     */
    final protected function getTemplating()
    {
        return $this->templatingProvider->getService();
    }
}
