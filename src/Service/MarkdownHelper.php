<?php

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    /**
     * @var MarkdownInterface
     */
    private $markdown;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MarkdownInterface $markdown
     * @param AdapterInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        MarkdownInterface $markdown,
        AdapterInterface $cache,
        LoggerInterface $logger
    ) {
        $this->markdown = $markdown;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param string $source
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $source): string
    {
        if (empty($source)) {
            $this->logger->info('Empty content');
        }

        $item = $this->cache->getItem('markdown_'.md5($source));

        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}