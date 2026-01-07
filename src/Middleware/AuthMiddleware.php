<?php

namespace SolidApi\Middleware;

use WP_REST_Request;
use WP_Error;

class AuthMiddleware {
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(WP_REST_Request $request) {
        return is_user_logged_in();
    }

    /**
     * Check if user has specific capability
     */
    public static function hasCapability(string $capability) {
        return function(WP_REST_Request $request) use ($capability) {
            if (!is_user_logged_in()) {
                return new WP_Error(
                    'rest_forbidden',
                    __('You must be logged in to access this resource.'),
                    ['status' => 401]
                );
            }

            if (!current_user_can($capability)) {
                return new WP_Error(
                    'rest_forbidden',
                    __('You do not have permission to access this resource.'),
                    ['status' => 403]
                );
            }

            return true;
        };
    }

    /**
     * Verify nonce for logged in users
     */
    public static function verifyNonce(WP_REST_Request $request) {
        // Get nonce from header or parameter
        $nonce = $request->get_header('X-WP-Nonce') ?? $request->get_param('_wpnonce');

        if (!$nonce) {
            return new WP_Error(
                'rest_cookie_invalid_nonce',
                __('Nonce is missing.'),
                ['status' => 403]
            );
        }

        // Verify nonce
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error(
                'rest_cookie_invalid_nonce',
                __('Invalid nonce.'),
                ['status' => 403]
            );
        }

        return true;
    }

    /**
     * Check if user can manage student books
     * Supports: Cookie Auth, Application Passwords, Basic Auth
     */
    public static function canManageStudentBooks(WP_REST_Request $request) {
        // Check if already authenticated via WordPress
        if (is_user_logged_in()) {
            // User is logged in, check permissions
            if (current_user_can('manage_options') || current_user_can('edit_posts')) {
                return true;
            }
            
            return new WP_Error(
                'rest_forbidden',
                __('You do not have permission to manage student books.'),
                ['status' => 403]
            );
        }

        // If not logged in via cookies, return auth error with helpful message
        return new WP_Error(
            'rest_forbidden',
            __('Authentication required. Please use one of these methods: 1) WordPress Application Password (recommended), 2) Cookie-based authentication, or 3) Login first at /wp-admin'),
            ['status' => 401]
        );
    }

    /**
     * Combined check: Logged in + Nonce verification
     */
    public static function authenticateUser(WP_REST_Request $request) {
        // Check if logged in
        if (!is_user_logged_in()) {
            return new WP_Error(
                'rest_forbidden',
                __('You must be logged in.'),
                ['status' => 401]
            );
        }

        // Verify nonce for cookie authentication
        return self::verifyNonce($request);
    }
}
