<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Customer
 *
 * Handles customer data operations including branch-wise report fetching.
 *
 * @package App\Models
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    /**
     * Sales relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
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
            $query->whereHas('sales', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query->withCount(['sales'])->get();
    }
}
