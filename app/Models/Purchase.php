<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $table = 'purchases';
    const PER_PAGE = 20;

    protected $fillable = [
        'code',
        'branch_id',
        'supplier_id',
        'product_info',
        'purchase_date',
        'total_price',
        'discount',
        'paid',
        'due'
    ];

    protected $casts = [
        'product_info'  => 'array',
        'purchase_date' => 'datetime',
        'total_price'   => 'decimal:2',
        'discount'      => 'decimal:2',
        'paid'          => 'decimal:2',
        'due'           => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public static function getPurchaseReport(?int $branch_id = null)
    {
        return self::query()
            ->with(['product', 'supplier', 'branch'])
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->orderByDesc('purchase_date')
            ->paginate(self::PER_PAGE);
    }
}

