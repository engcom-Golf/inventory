<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCache\Model;

use Magento\Framework\EntityManager\EventManager;
use Magento\Framework\Indexer\CacheContextFactory;
use Magento\Framework\App\CacheInterface;

/**
 * Clean cache for given product ids.
 */
class FlushCacheByProductIds
{
    /**
     * @var CacheContextFactory
     */
    private $cacheContextFactory;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var string
     */
    private $productCacheTag;

    /**
     * @var CacheInterface
     */
    private $appCache;

    /**
     * @param CacheContextFactory $cacheContextFactory
     * @param EventManager $eventManager
     * @param string $productCacheTag
     * @param CacheInterface $appCache
     */
    public function __construct(
        CacheContextFactory $cacheContextFactory,
        EventManager $eventManager,
        string $productCacheTag,
        CacheInterface $appCache
    ) {
        $this->cacheContextFactory = $cacheContextFactory;
        $this->eventManager = $eventManager;
        $this->productCacheTag = $productCacheTag;
        $this->appCache = $appCache;
    }

    /**
     * Clean cache for given product ids.
     *
     * @param array $productIds
     * @return void
     */
    public function execute(array $productIds)
    {
        if ($productIds) {
            $cacheContext = $this->cacheContextFactory->create();
            $cacheContext->registerEntities($this->productCacheTag, $productIds);
            $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $cacheContext]);
            $this->appCache->clean($cacheContext->getIdentities());
        }
    }
}
