<?php

namespace SolidApi\Validators;

use SolidApi\Abstracts\AbstractValidator;

class StudentBookValidator extends AbstractValidator {
    
    /**
     * Get validation rules for StudentBook
     * 
     * @return array
     */
    protected function getRules(): array {
        return [
            'student_name' => ['required', 'string', 'min:2', 'max:255'],
            'book_title' => ['required', 'string', 'min:2', 'max:255'],
            'isbn' => ['optional', 'string', 'max:50'],
            'borrowed_date' => ['optional', 'date'],
            'return_date' => ['optional', 'date'],
        ];
    }

    /**
     * Get validation rules for update operation
     * 
     * @return array
     */
    public function getUpdateRules(): array {
        return [
            'student_name' => ['optional', 'string', 'min:2', 'max:255'],
            'book_title' => ['optional', 'string', 'min:2', 'max:255'],
            'isbn' => ['optional', 'string', 'max:50'],
            'borrowed_date' => ['optional', 'date'],
            'return_date' => ['optional', 'date'],
        ];
    }

    /**
     * Validate for create operation
     * 
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function validateCreate(array $data): array {
        // Ensure required fields are present
        if (!isset($data['student_name']) || !isset($data['book_title'])) {
            throw new \Exception('Student name and book title are required for creating a record');
        }

        return $this->validate($data);
    }

    /**
     * Validate for update operation
     * 
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function validateUpdate(array $data): array {
        // At least one field must be provided for update
        if (empty($data)) {
            throw new \Exception('At least one field is required for updating a record');
        }

        return $this->validate($data, $this->getUpdateRules());
    }

    /**
     * Custom validation for ISBN format (optional)
     * 
     * @param string $field
     * @param mixed $value
     * @param mixed $param
     * @param array &$validated
     * @param array $data
     */
    protected function validateIsbn(string $field, $value, $param, array &$validated, array $data): void {
        if (empty($value)) {
            return;
        }

        // Remove hyphens and spaces
        $isbn = str_replace(['-', ' '], '', $value);

        // Check if ISBN-10 or ISBN-13
        if (!preg_match('/^\d{10}$|^\d{13}$/', $isbn)) {
            $this->addError('ISBN must be a valid ISBN-10 or ISBN-13 format');
            return;
        }

        $validated[$field] = sanitize_text_field($value);
    }

    /**
     * Custom validation to ensure return date is after borrowed date
     * 
     * @param array $data
     * @return bool
     */
    public function validateDateRange(array $data): bool {
        if (isset($data['borrowed_date']) && isset($data['return_date'])) {
            $borrowedDate = strtotime($data['borrowed_date']);
            $returnDate = strtotime($data['return_date']);

            if ($returnDate <= $borrowedDate) {
                $this->addError('Return date must be after borrowed date');
                return false;
            }
        }

        return true;
    }
}
