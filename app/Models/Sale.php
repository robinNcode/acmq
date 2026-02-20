<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Models\Customer;

/**
 * Class Sale
 *
 * Handles sale report fetching with optional branch filtering.
 *
 * @package App\Models
 */
class Sale extends Model
{
    use SoftDeletes;

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
     * @return Collection
     */
    public static function getSaleReport(?int $branchId = null): Collection
    {
        $query = self::query()
            ->with(['customer'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderByDesc('selling_date');

        return $query->get();
    }

    /**
     * Customer relationship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
