<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Models\Customer;
use LaravelIdea\Helper\App\Models\_IH_Sale_C;

/**
 * Class SaleController
 *
 * Handles sale report fetching with optional branch filtering.
 *
 * @package App\Models
 */
class Sale extends Model
{
    use SoftDeletes;

    const PER_PAGE = 20;
    protected $table = 'sales';

    protected $casts = [
        'product_info' => 'array',
        'selling_date' => 'datetime',
        'total_price'  => 'decimal:2',
        'discount'     => 'decimal:2',
        'paid'         => 'decimal:2',
        'due'          => 'decimal:2',
    ];

    /**
     * Fetch sales report filtered by branch ID.
     *
     * If branch ID is null, returns all sales.
     *
     * @param int|null $branchId
     * @return LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|_IH_Sale_C|Sale[]
     */
    public static function getSaleReport(?int $branchId = null): array|LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|_IH_Sale_C
    {
        return self::query()
            ->with(['customer'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderByDesc('selling_date')
            ->paginate(self::PER_PAGE);
    }

    /**
     * CustomerController relationship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
