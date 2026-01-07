<?php

namespace SolidApi\Services;

use SolidApi\Abstracts\AbstractService;
use SolidApi\Repositories\StudentBookRepository;
use SolidApi\Validators\StudentBookValidator;

class StudentBookService extends AbstractService {
    
    protected $validator;

    /**
     * Constructor with dependency injection
     * 
     * @param StudentBookRepository $repository
     * @param StudentBookValidator|null $validator
     */
    public function __construct(StudentBookRepository $repository, ?StudentBookValidator $validator = null) {
        parent::__construct($repository);
        $this->validator = $validator ?? new StudentBookValidator();
    }

    /**
     * Create a new student book record
     * 
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function create(array $data) {
        // Validate using dedicated validator
        $validatedData = $this->validator->validateCreate($data);
        
        // Additional business logic validation (date range check)
        if (!$this->validator->validateDateRange($validatedData)) {
            throw new \Exception(implode(', ', $this->validator->getErrors()));
        }

        return $this->repository->create($validatedData);
    }

    /**
     * Update an existing record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function update(int $id, array $data): bool {
        // Validate using dedicated validator
        $validatedData = $this->validator->validateUpdate($data);
        
        // Additional business logic validation (date range check)
        if (!$this->validator->validateDateRange($validatedData)) {
            throw new \Exception(implode(', ', $this->validator->getErrors()));
        }

        return $this->repository->update($id, $validatedData);
    }

    /**
     * Validate data before processing (for compatibility with AbstractService)
     * This method is now delegated to the validator
     * 
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function validate(array $data): array {
        return $this->validator->validate($data);
    }
}
