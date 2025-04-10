<?php declare(strict_types=1);

namespace VlRemoveItemFromCategory\Core\Content\Product\SalesChannel\Listing;

use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingRoute;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingRouteResponse;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Decorator for the product listing route to ensure products are only shown in their assigned categories
 */
class ProductListingRouteDecorator extends ProductListingRoute
{
    /**
     * @var ProductListingRoute
     */
    private $decorated;

    /**
     * @param ProductListingRoute $decorated
     */
    public function __construct(ProductListingRoute $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @param string $categoryId
     * @param Request $request
     * @param SalesChannelContext $context
     * @param Criteria $criteria
     * @return ProductListingRouteResponse
     */
    public function load(string $categoryId, Request $request, SalesChannelContext $context, Criteria $criteria): ProductListingRouteResponse
    {
        if ($categoryId) {
            // Add a filter to ensure products are directly assigned to this category
            // This ensures products are only shown in categories they're explicitly assigned to
            $criteria->addFilter(new EqualsFilter('product.categories.id', $categoryId));
        }
        
        return $this->decorated->load($categoryId, $request, $context, $criteria);
    }

    /**
     * Get the decorated route
     *
     * @return ProductListingRoute
     */
    public function getDecorated(): ProductListingRoute
    {
        return $this->decorated;
    }
}
