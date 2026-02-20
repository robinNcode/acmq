<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelIdea\Helper\App\Models\_IH_Purchase_C;
use LaravelIdea\Helper\App\Models\_IH_Sale_C;

class Purchase extends Model
{
    use SoftDeletes;

    protected $table = 'purchases';
    const PER_PAGE = 20;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total_price',
        'branch_id'
    ];

    protected $casts = [
        'product_info' => 'array',
        'purchase_date' => 'datetime',
        'price'        => 'decimal:2',
        'total_price'  => 'decimal:2',
        'quantity'     => 'integer',
        'created_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function getPurchaseReport(?int $branch_id = null): array|LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|_IH_Sale_C
    {
        return self::query()
            ->with(['product', 'supplier'])
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->orderByDesc('purchase_date')
            ->paginate(self::PER_PAGE);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
