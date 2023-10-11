<?php

namespace App\Service;


use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MixRepository
{
//    private HttpClientInterface $httpClient;
//    private CacheInterface $cache;

    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface      $cache,
                                    #[Autowire('%kernel.debug%')]
        private bool $isDebug,
//        #[Autowire('@twig.command.debug')]
        #[Autowire(service: 'twig.command.debug')]
        private DebugCommand        $twigDebugCommand
    )
    {
//        $this->cache = $cache;
//        $this->httpClient = $httpClient;
    }

    public function findAll(): array
    {
        return $this->cache->get('mixed_data', function (CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug === true ? 5 : 60); // hết hạn sau 5s
            return $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json')->toArray();
        });
    }
}