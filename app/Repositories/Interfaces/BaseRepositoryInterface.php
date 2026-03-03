<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all instances.
     */
    public function all();

    /**
     * Find an instance by ID.
     */
    public function find($id);

    /**
     * Create a new instance.
     */
    public function create(array $data);

    /**
     * Update an instance by ID.
     */
    public function update($id, array $data);

    /**
     * Delete an instance by ID.
     */
    public function delete($id);

    /**
     * Get paginated instances.
     */
    public function paginate($perPage = 30);
}
