<?php
/**
 * Plugin Name: Solid API
 * Plugin URI: https://example.com
 * Description: A CRUD API following SOLID architecture principles
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: solid-api
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SOLID_API_VERSION', '1.0.0');
define('SOLID_API_PATH', plugin_dir_path(__FILE__));
define('SOLID_API_URL', plugin_dir_url(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'SolidApi\\';
    $base_dir = SOLID_API_PATH . 'src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Include migration
require_once SOLID_API_PATH . 'database/Migration.php';

use SolidApi\Database\Migration;
use SolidApi\Repositories\StudentBookRepository;
use SolidApi\Services\StudentBookService;
use SolidApi\Controllers\StudentBookController;
use SolidApi\Validators\StudentBookValidator;

/**
 * Plugin activation hook
 */
register_activation_hook(__FILE__, function() {
    Migration::run();
    flush_rewrite_rules();
});

/**
 * Plugin deactivation hook
 */
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

/**
 * Initialize the plugin
 */
add_action('rest_api_init', function() {
    // Dependency Injection - Validator -> Repository -> Service -> Controller
    $validator = new StudentBookValidator();
    $repository = new StudentBookRepository();
    $service = new StudentBookService($repository, $validator);
    $controller = new StudentBookController($service);
    
    // Register routes
    $controller->registerRoutes();
});
