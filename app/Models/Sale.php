<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Models\Customer;
use App\Models\Branch;

class Sale extends Model
{
    use SoftDeletes;

    const PER_PAGE = 20;
    protected $table = 'sales';

    protected $fillable = [
        'code',
        'branch_id',
        'customer_id',
        'product_info',
        'selling_date',
        'total_price',
        'discount',
        'paid',
        'due',
    ];

    protected $casts = [
        'product_info' => 'array',
        'selling_date' => 'datetime',
        'total_price'  => 'decimal:2',
        'discount'     => 'decimal:2',
        'paid'         => 'decimal:2',
        'due'          => 'decimal:2',
    ];

    public static function getSaleReport(?int $branchId = null)
    {
        return self::query()
            ->with(['customer', 'branch'])
            ->when($branchId, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderByDesc('selling_date')
            ->paginate(self::PER_PAGE);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

