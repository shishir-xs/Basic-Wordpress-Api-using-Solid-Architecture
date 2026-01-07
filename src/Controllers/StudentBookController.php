<?php

namespace SolidApi\Controllers;

use SolidApi\Abstracts\AbstractController;
use SolidApi\Middleware\AuthMiddleware;
use WP_REST_Request;
use WP_REST_Response;

class StudentBookController extends AbstractController {
    
    /**
     * Register routes
     */
    public function registerRoutes(): void {
        // Get all student books (Public - No authentication required)
        register_rest_route($this->namespace, '/student-books', [
            'methods' => 'GET',
            'callback' => [$this, 'handleGetAll'],
            'permission_callback' => '__return_true'
        ]);

        // Create student book (Authentication required)
        register_rest_route($this->namespace, '/student-books', [
            'methods' => 'POST',
            'callback' => [$this, 'handleCreate'],
            'permission_callback' => [AuthMiddleware::class, 'canManageStudentBooks']
        ]);

        // Update student book (Authentication required)
        register_rest_route($this->namespace, '/student-books/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'handleUpdate'],
            'permission_callback' => [AuthMiddleware::class, 'canManageStudentBooks']
        ]);

        // Delete student book (Authentication required)
        register_rest_route($this->namespace, '/student-books/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'handleDelete'],
            'permission_callback' => [AuthMiddleware::class, 'canManageStudentBooks']
        ]);
    }
}
