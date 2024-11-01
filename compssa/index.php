<?php

// Validate controller and action input
$controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Define routing logic
$routes = [
    'StudentController' => [
        'index',
        'create',
        'store',
        'edit',
        'update',
        'delete'
    ],
    'PaymentController' => [
        'index',
        'create',
        'store',
        'edit',
        'update',
        'delete'
    ]
];

// Check if controller and action are valid
if (array_key_exists($controller, $routes) && in_array($action, $routes[$controller])) {
    try {
        // Instantiate controller and call action
        $controllerClass = ucfirst($controller);
        $controllerObject = new $controllerClass();
        
        if ($action === 'edit' || $action === 'delete') {
            $controllerObject->$action($id);
        } else {
            $controllerObject->$action();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid controller or action!";
}

?>
