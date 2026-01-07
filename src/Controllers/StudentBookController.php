<?php

namespace SolidApi\Controllers;

use SolidApi\Abstracts\AbstractController;
use WP_REST_Request;
use WP_REST_Response;

class StudentBookController extends AbstractController {
    
    /**
     * Register routes
     */
    public function registerRoutes(): void {
        // Get all student books
        register_rest_route($this->namespace, '/student-books', [
            'methods' => 'GET',
            'callback' => [$this, 'handleGetAll'],
            'permission_callback' => '__return_true'
        ]);

        // Create student book
        register_rest_route($this->namespace, '/student-books', [
            'methods' => 'POST',
            'callback' => [$this, 'handleCreate'],
            'permission_callback' => '__return_true'
        ]);

        // Update student book
        register_rest_route($this->namespace, '/student-books/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'handleUpdate'],
            'permission_callback' => '__return_true'
        ]);

        // Delete student book
        register_rest_route($this->namespace, '/student-books/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'handleDelete'],
            'permission_callback' => '__return_true'
        ]);
    }
}
