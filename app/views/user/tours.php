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
    <title>Blog View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h5 class="card-title">Blog Title Here</h5>
                        <p class="card-text">This is a short preview of the blog post. The full content can be read by
                            clicking the button below.</p>
                        <a href="blog-details.php?id=1" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>