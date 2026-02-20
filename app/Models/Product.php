<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Product
 *
 * Handles product data operations including branch-wise report fetching.
 *
 * @package App\Models
 */
class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'entry_by',
        'approved_by'
    ];

    /**
     * Fetch products sold branch-wise.
     * If branch ID is null, returns all products.
     *
     * NOTE:
     * Since products are stored inside sales.product_info JSON,
     * this method extracts product IDs from sales first.
     *
     * @param int|null $branchId
     * @return Collection
     */
    public static function getProductsByBranch(?int $branchId = null): Collection
    {
        $salesQuery = Sale::query();

        if ($branchId) {
            $salesQuery->where('branch_id', $branchId);
        }

        $sales = $salesQuery->get(['product_info']);

        $productIds = [];

        foreach ($sales as $sale) {
            foreach ($sale->product_info as $product) {
                if (isset($product['product_id'])) {
                    $productIds[] = $product['product_id'];
                }
            }
        }

        $productIds = array_unique($productIds);

        return self::whereIn('id', $productIds)->get();
    }
}
