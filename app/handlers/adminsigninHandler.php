<?php

require_once __DIR__ . '/../controllers/adminController.php';

use App\Controllers\AdminController;

// Create instance of UserController
$userController = new AdminController();
// Call register method to handle signup
$userController->login();
?>