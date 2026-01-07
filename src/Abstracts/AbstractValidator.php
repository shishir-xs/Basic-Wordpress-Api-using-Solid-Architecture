<?php

namespace SolidApi\Abstracts;

use SolidApi\Interfaces\ValidatorInterface;

abstract class AbstractValidator implements ValidatorInterface {
    
    protected $errors = [];

    /**
     * Validate data
     * 
     * @param array $data
     * @param array $rules
     * @return array
     * @throws \Exception
     */
    public function validate(array $data, array $rules = []): array {
        $this->errors = [];
        $validated = [];

        // Use custom rules if provided, otherwise use default rules
        $rulesToApply = !empty($rules) ? $rules : $this->getRules();

        foreach ($rulesToApply as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule, $validated, $data);
            }
        }

        if ($this->hasErrors()) {
            throw new \Exception(implode(', ', $this->errors));
        }

        return $validated;
    }

    /**
     * Apply validation rule
     * 
     * @param string $field
     * @param mixed $value
     * @param string $rule
     * @param array &$validated
     * @param array $data
     */
    protected function applyRule(string $field, $value, string $rule, array &$validated, array $data): void {
        // Parse rule and parameters (e.g., "max:255" -> rule: "max", param: "255")
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleParam = $ruleParts[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
                    return;
                }
                $validated[$field] = $this->sanitize($field, $value);
                break;

            case 'optional':
                if (isset($data[$field])) {
                    $validated[$field] = $this->sanitize($field, $value);
                }
                break;

            case 'string':
                if (isset($data[$field]) && !is_string($value)) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a string";
                    return;
                }
                break;

            case 'numeric':
                if (isset($data[$field]) && !is_numeric($value)) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be numeric";
                    return;
                }
                break;

            case 'email':
                if (isset($data[$field]) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid email";
                    return;
                }
                break;

            case 'min':
                if (isset($data[$field]) && strlen($value) < (int)$ruleParam) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$ruleParam} characters";
                    return;
                }
                break;

            case 'max':
                if (isset($data[$field]) && strlen($value) > (int)$ruleParam) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$ruleParam} characters";
                    return;
                }
                break;

            case 'date':
                if (isset($data[$field]) && !$this->isValidDate($value)) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid date (Y-m-d format)";
                    return;
                }
                break;

            case 'in':
                $allowedValues = explode(',', $ruleParam);
                if (isset($data[$field]) && !in_array($value, $allowedValues)) {
                    $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be one of: " . implode(', ', $allowedValues);
                    return;
                }
                break;

            default:
                // Allow custom rules defined in child class
                $customMethod = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $customMethod)) {
                    $this->$customMethod($field, $value, $ruleParam, $validated, $data);
                }
                break;
        }
    }

    /**
     * Sanitize field value
     * 
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    protected function sanitize(string $field, $value) {
        if (is_string($value)) {
            return sanitize_text_field($value);
        }
        return $value;
    }

    /**
     * Check if date is valid
     * 
     * @param string $date
     * @param string $format
     * @return bool
     */
    protected function isValidDate(string $date, string $format = 'Y-m-d'): bool {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Check if validation has errors
     * 
     * @return bool
     */
    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    /**
     * Add custom error
     * 
     * @param string $message
     */
    protected function addError(string $message): void {
        $this->errors[] = $message;
    }

    /**
     * Get validation rules (to be implemented by child class)
     * 
     * @return array
     */
    abstract protected function getRules(): array;
}
