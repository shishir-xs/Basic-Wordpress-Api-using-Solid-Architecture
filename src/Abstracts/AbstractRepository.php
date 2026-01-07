<?php

namespace SolidApi\Abstracts;

use SolidApi\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface {
    
    protected $wpdb;
    protected $table;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->getTableName();
    }

    /**
     * Get the table name
     * 
     * @return string
     */
    abstract protected function getTableName(): string;

    /**
     * Create a new record
     * 
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        $this->wpdb->insert($this->table, $data);
        return $this->wpdb->insert_id;
    }

    /**
     * Update an existing record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        return $this->wpdb->update(
            $this->table,
            $data,
            ['id' => $id]
        ) !== false;
    }

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        return $this->wpdb->delete(
            $this->table,
            ['id' => $id]
        ) !== false;
    }

    /**
     * Find a record by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function find(int $id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id = %d",
                $id
            )
        );
    }

    /**
     * Get all records
     * 
     * @return array
     */
    public function findAll(): array {
        $results = $this->wpdb->get_results(
            "SELECT * FROM {$this->table} ORDER BY id DESC"
        );
        return $results ? $results : [];
    }
}
