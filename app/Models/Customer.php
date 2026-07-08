<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Customer
 *
 * Handles customer data operations including branch-wise filtering.
 *
 * @package App\Models
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * Branch relationship.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Sales relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Fetch customers branch-wise.
     * If branch ID is null, returns all customers.
     *
     * @param int|null $branchId
     * @return Collection
     */
    public static function getCustomersByBranch(?int $branchId = null): Collection
    {
        $query = self::query();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->withCount(['sales'])->get();
    }
}
