<?php

namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    private $isDebug;

    public function __construct(MarkdownInterface $markdown, AdapterInterface $cache, LoggerInterface $markdownLogger, bool $isDebug)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    /**
     * @param string $src
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $src): string
    {
        if (stripos($src, 'beacon') !== false) {
            $this->logger->info('They are talking about beacon again!');
        }

        if ($this->isDebug) {
            return $this->markdown->transform($src);
        }

        $item = $this->cache->getItem('markdown_'.md5($src));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($src));
            $this->cache->save($item);
        }

        return $item->get();
    }
}