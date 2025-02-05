<?php

define('BASE_URL', '/KD-Enterprise/jetwing-travels-clone');

// Include the necessary middleware for authentication
require_once __DIR__ . '/../../middleware/adminauthMiddleware.php';

use App\Middleware\adminauthMiddleware;

// Authenticate the user before accessing the admin dashboard
adminauthMiddleware::authenticate(['admin']);  // Only allow 'admin' role to access the dashboard

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Sign-Up Form -->
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="text-center">Admin Sign Up</h4>
                        <form action="../../handlers/adminsignupHandler.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Sign Up</button>
                        </form>
                        <!-- Sign In Link -->
                        <div class="text-center mt-3">
                            <p>Have an account?<a href="./signin.php">login here</a> </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>