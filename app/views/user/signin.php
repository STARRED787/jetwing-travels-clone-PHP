<?php

// Include the necessary middleware for authentication
require_once __DIR__ . '/../../middleware/userauthMiddleware.php';

use App\Middleware\userauthMiddleware;

// Authenticate the user before accessing the admin dashboard
userauthMiddleware::authenticate(['user']);  // Only allow 'admin' role to access the dashboard

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign IN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <!-- Updated Login Form -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="text-center">Login</h4>
                        <!-- Update the form action to point to your actual login handler -->
                        <form id="loginForm" method="POST" action="../../handlers/usersigninhandler.php">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <!-- Sign Up Link -->
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="./signup.php">Sign up</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Add login handling script -->
    <script>

    </script>
</body>

</html>