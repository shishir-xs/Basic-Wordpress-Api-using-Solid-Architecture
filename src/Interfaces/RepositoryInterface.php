<?php

namespace SolidApi\Interfaces;

interface RepositoryInterface {
    
    /**
     * Create a new record
     * 
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find a record by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Get all records
     * 
     * @return array
     */
    public function findAll(): array;
}
