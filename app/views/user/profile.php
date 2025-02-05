<?php

// Include the necessary middleware for authentication
require_once __DIR__ . '/../../middleware/userauthMiddleware.php';

use App\Middleware\userauthMiddleware;

// Authenticate the user before accessing the admin dashboard
userauthMiddleware::authenticate(['user']);  // Only allow 'admin' role to access the dashboard

// Your admin dashboard content goes here
echo "Welcome, user! This is your profile.";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
</head>

<body>
    <h1>User profile</h1>
</body>

</html>