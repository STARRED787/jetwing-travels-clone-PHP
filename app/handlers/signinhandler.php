<?php

require_once __DIR__ . '/../controllers/UserController.php';

use App\Controllers\UserController;

// Create instance of UserController
$userController = new UserController();
// Call register method to handle signup
$userController->login();
?>