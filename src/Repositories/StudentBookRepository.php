<?php

namespace SolidApi\Repositories;

use SolidApi\Abstracts\AbstractRepository;
use SolidApi\Models\StudentBook;

class StudentBookRepository extends AbstractRepository {
    
    /**
     * Get the table name
     * 
     * @return string
     */
    protected function getTableName(): string {
        return $this->wpdb->prefix . 'student_books';
    }

    /**
     * Find a record by ID and return as model
     * 
     * @param int $id
     * @return StudentBook|null
     */
    public function find(int $id) {
        $row = parent::find($id);
        return $row ? StudentBook::fromRow($row) : null;
    }

    /**
     * Create a new student book record
     * 
     * @param array $data
     * @return int
     */
    public function create(array $data) {
        return parent::create($data);
    }
}
