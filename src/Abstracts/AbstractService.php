<?php

namespace SolidApi\Abstracts;

use SolidApi\Interfaces\ServiceInterface;
use SolidApi\Interfaces\RepositoryInterface;

abstract class AbstractService implements ServiceInterface {
    
    protected $repository;

    public function __construct(RepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Create a new record
     * 
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        $validatedData = $this->validate($data);
        return $this->repository->create($validatedData);
    }

    /**
     * Update an existing record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $validatedData = $this->validate($data);
        return $this->repository->update($id, $validatedData);
    }

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        return $this->repository->delete($id);
    }

    /**
     * Get a record by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function getById(int $id) {
        return $this->repository->find($id);
    }

    /**
     * Get all records
     * 
     * @return array
     */
    public function getAll(): array {
        return $this->repository->findAll();
    }

    /**
     * Validate data before processing
     * 
     * @param array $data
     * @return array
     */
    abstract protected function validate(array $data): array;
}
