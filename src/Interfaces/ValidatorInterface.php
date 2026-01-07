<?php

namespace SolidApi\Interfaces;

interface ValidatorInterface {
    
    /**
     * Validate data
     * 
     * @param array $data
     * @param array $rules
     * @return array
     * @throws \Exception
     */
    public function validate(array $data, array $rules = []): array;

    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors(): array;

    /**
     * Check if validation has errors
     * 
     * @return bool
     */
    public function hasErrors(): bool;
}
