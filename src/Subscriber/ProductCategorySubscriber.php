<?php declare(strict_types=1);

namespace VlRemoveItemFromCategory\Subscriber;

use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber to handle product category assignments
 */
class ProductCategorySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepository
     */
    private $productRepository;

    /**
     * @var EntityRepository
     */
    private $productCategoryRepository;

    /**
     * @param EntityRepository $productRepository
     * @param EntityRepository $productCategoryRepository
     */
    public function __construct(
        EntityRepository $productRepository,
        EntityRepository $productCategoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_CATEGORY_WRITTEN_EVENT => 'onProductCategoryWritten',
            ProductEvents::PRODUCT_CATEGORY_DELETED_EVENT => 'onProductCategoryDeleted',
        ];
    }

    /**
     * Handle product category assignments
     *
     * @param EntityWrittenEvent $event
     */
    public function onProductCategoryWritten(EntityWrittenEvent $event): void
    {
        // This method handles when new categories are assigned to products
        // No action needed here as products should be visible in newly assigned categories
    }

    /**
     * Handle product category removals
     *
     * @param EntityWrittenEvent $event
     */
    public function onProductCategoryDeleted(EntityWrittenEvent $event): void
    {
        // Get the affected product-category associations
        $deletedAssociations = $event->getWriteResults();
        
        foreach ($deletedAssociations as $result) {
            $payload = $result->getPayload();
            
            // Skip if we don't have both product and category IDs
            if (!isset($payload['productId']) || !isset($payload['categoryId'])) {
                continue;
            }
            
            $productId = $payload['productId'];
            $categoryId = $payload['categoryId'];
            
            // Check if the product still exists in other categories
            // If it does, we don't need to do anything special
            // If it doesn't, we need to ensure it's not visible in the removed category
            
            // This is handled automatically by Shopware's core functionality
            // as products are only displayed in categories they're assigned to
        }
    }
}
