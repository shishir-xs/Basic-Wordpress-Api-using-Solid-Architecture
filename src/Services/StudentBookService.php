<?php

namespace SolidApi\Services;

use SolidApi\Abstracts\AbstractService;
use SolidApi\Repositories\StudentBookRepository;

class StudentBookService extends AbstractService {
    
    /**
     * Validate data before processing
     * 
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function validate(array $data): array {
        $validated = [];

        // Validate student_name
        if (isset($data['student_name'])) {
            if (empty(trim($data['student_name']))) {
                throw new \Exception('Student name is required');
            }
            $validated['student_name'] = sanitize_text_field($data['student_name']);
        }

        // Validate book_title
        if (isset($data['book_title'])) {
            if (empty(trim($data['book_title']))) {
                throw new \Exception('Book title is required');
            }
            $validated['book_title'] = sanitize_text_field($data['book_title']);
        }

        // Validate isbn (optional)
        if (isset($data['isbn'])) {
            $validated['isbn'] = sanitize_text_field($data['isbn']);
        }

        // Validate borrowed_date (optional)
        if (isset($data['borrowed_date'])) {
            $validated['borrowed_date'] = sanitize_text_field($data['borrowed_date']);
        }

        // Validate return_date (optional)
        if (isset($data['return_date'])) {
            $validated['return_date'] = sanitize_text_field($data['return_date']);
        }

        return $validated;
    }

    /**
     * Create a new student book record
     * 
     * @param array $data
     * @return int
     */
    public function create(array $data) {
        // Ensure required fields for creation
        if (!isset($data['student_name']) || !isset($data['book_title'])) {
            throw new \Exception('Student name and book title are required');
        }

        return parent::create($data);
    }
}
