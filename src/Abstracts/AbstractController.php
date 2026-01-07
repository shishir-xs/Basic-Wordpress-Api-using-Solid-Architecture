<?php

namespace SolidApi\Abstracts;

use WP_REST_Request;
use WP_REST_Response;
use SolidApi\Interfaces\ServiceInterface;

abstract class AbstractController {
    
    protected $service;
    protected $namespace = 'solid-api/v1';

    public function __construct(ServiceInterface $service) {
        $this->service = $service;
    }

    /**
     * Register routes
     */
    abstract public function registerRoutes(): void;

    /**
     * Handle create request
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handleCreate(WP_REST_Request $request): WP_REST_Response {
        try {
            $data = $request->get_json_params();
            $result = $this->service->create($data);
            
            return new WP_REST_Response([
                'success' => true,
                'data' => $result,
                'message' => 'Record created successfully'
            ], 201);
        } catch (\Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle update request
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handleUpdate(WP_REST_Request $request): WP_REST_Response {
        try {
            $id = (int) $request->get_param('id');
            $data = $request->get_json_params();
            $result = $this->service->update($id, $data);
            
            return new WP_REST_Response([
                'success' => true,
                'data' => $result,
                'message' => 'Record updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle delete request
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handleDelete(WP_REST_Request $request): WP_REST_Response {
        try {
            $id = (int) $request->get_param('id');
            $result = $this->service->delete($id);
            
            return new WP_REST_Response([
                'success' => true,
                'message' => 'Record deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle get all request
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function handleGetAll(WP_REST_Request $request): WP_REST_Response {
        try {
            $data = $this->service->getAll();
            
            return new WP_REST_Response([
                'success' => true,
                'data' => $data,
                'message' => 'Records retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
