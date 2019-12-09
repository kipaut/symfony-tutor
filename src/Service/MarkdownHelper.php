<?php

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

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
    private $markdownLogger;
    /**
     * @var Security
     */
    private $security;

    /**
     * @param MarkdownInterface $markdown
     * @param AdapterInterface $cache
     * @param LoggerInterface $markdownLogger
     * @param Security $security
     */
    public function __construct(
        MarkdownInterface $markdown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger,
        Security $security
    ) {
        $this->markdown = $markdown;
        $this->cache = $cache;
        $this->markdownLogger = $markdownLogger;
        $this->security = $security;
    }

    /**
     * @param string $source
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $source): string
    {
        if (empty($source)) {
            $this->markdownLogger->info(
                'Empty content',
                [
                    'user' => $this->security->getUser(),
                ]
            );
        }

        $item = $this->cache->getItem('markdown_'.md5($source));

        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}