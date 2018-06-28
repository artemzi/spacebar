<?php

namespace App\Service;


use Michelf\MarkdownInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;

    public function __construct(MarkdownInterface $markdown, AdapterInterface $cache)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
    }

    /**
     * @param string $src
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $src): string
    {
        $item = $this->cache->getItem('markdown_'.md5($src));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($src));
            $this->cache->save($item);
        }

        return $item->get();
    }
}