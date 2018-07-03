<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    /**
     * @var MarkdownHelper
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $value
     * @return string
     */
    public function processMarkdown($value): string
    {
        return $this->container
            ->get(MarkdownHelper::class)
            ->parse($value);
    }

    /**
     * Returns an array of service types required by such instances, optionally keyed by the service names used internally.
     *
     * For mandatory dependencies:
     *
     *  * array('logger' => 'Psr\Log\LoggerInterface') means the objects use the "logger" name
     *    internally to fetch a service which must implement Psr\Log\LoggerInterface.
     *  * array('Psr\Log\LoggerInterface') is a shortcut for
     *  * array('Psr\Log\LoggerInterface' => 'Psr\Log\LoggerInterface')
     *
     * otherwise:
     *
     *  * array('logger' => '?Psr\Log\LoggerInterface') denotes an optional dependency
     *  * array('?Psr\Log\LoggerInterface') is a shortcut for
     *  * array('Psr\Log\LoggerInterface' => '?Psr\Log\LoggerInterface')
     *
     * @return array The required service types, optionally keyed by service names
     */
    public static function getSubscribedServices(): array
    {
        return [
            MarkdownHelper::class,
        ];
    }
}
