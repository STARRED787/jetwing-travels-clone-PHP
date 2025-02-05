<?php

// Include the necessary middleware for authentication
require_once __DIR__ . '/../../middleware/adminauthMiddleware.php';

use App\Middleware\adminauthMiddleware;

// Authenticate the user before accessing the admin dashboard
adminauthMiddleware::authenticate(['admin']);  // Only allow 'admin' role to access the dashboard

// Your admin dashboard content goes here
echo "Welcome, Admin! This is your dashboard.";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <h1>Admin dashboard</h1>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>