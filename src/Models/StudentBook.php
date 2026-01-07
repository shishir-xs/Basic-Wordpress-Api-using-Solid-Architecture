<?php

namespace SolidApi\Models;

class StudentBook {
    
    public $id;
    public $student_name;
    public $book_title;
    public $isbn;
    public $borrowed_date;
    public $return_date;
    public $created_at;
    public $updated_at;

    /**
     * Create instance from database row
     * 
     * @param object $row
     * @return self
     */
    public static function fromRow($row): self {
        $instance = new self();
        
        if ($row) {
            $instance->id = $row->id ?? null;
            $instance->student_name = $row->student_name ?? null;
            $instance->book_title = $row->book_title ?? null;
            $instance->isbn = $row->isbn ?? null;
            $instance->borrowed_date = $row->borrowed_date ?? null;
            $instance->return_date = $row->return_date ?? null;
            $instance->created_at = $row->created_at ?? null;
            $instance->updated_at = $row->updated_at ?? null;
        }
        
        return $instance;
    }

    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'student_name' => $this->student_name,
            'book_title' => $this->book_title,
            'isbn' => $this->isbn,
            'borrowed_date' => $this->borrowed_date,
            'return_date' => $this->return_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
