<?php

namespace App\Middleware;

require_once __DIR__ . '/../utils/JwtUtil.php';

use App\Utils\JwtUtil;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Exception;

define('BASE_URL', '/KD-Enterprise/blog-site');

class AuthMiddleware
{
    public static function authenticate($allowed_roles = [])
    {
        try {
            // Start session to store the token
            session_start();

            // Initialize JWT secret key
            JwtUtil::init();
            $secretKey = JwtUtil::getSecretKey();

            // Initialize the token variable to null
            $token = null;

            // Check if the token is available in the Authorization header
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                if (strpos($authHeader, 'Bearer ') === 0) {
                    $token = substr($authHeader, 7);
                }
            }

            // If no Authorization header, check for a cookie token
            if (!$token && isset($_COOKIE['jwt_token'])) {
                $token = $_COOKIE['jwt_token'];
                // Remove the JWT token from the cookie
                setcookie('jwt_token', '', time() - 3600, '/', '', false, true); // Expire the cookie
                unset($_COOKIE['jwt_token']); // Remove the token from $_COOKIE
            }

            // If no token is found, show an alert and redirect to login
            if (!$token) {
                echo "<script>alert('Token not provided. Please log in.'); window.location.href = '" . BASE_URL . "/index.php';</script>";
                exit;
            }

            // Ensure token is correctly formatted
            if (substr_count($token, '.') !== 2) {
                echo "<script>alert('Invalid token format.'); window.location.href = '" . BASE_URL . "/index.php';</script>";
                exit;
            }

            // Log the token for debugging
            error_log("Received Token: " . $token);

            // Decode JWT Token
            try {
                $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
                // Store token in session for later use
                $_SESSION['jwt_token'] = $token;
                error_log("Decoded Token: " . print_r($decoded, true));
            } catch (Exception $e) {
                // Log specific error message for decoding failure
                error_log("Error decoding token: " . $e->getMessage());
                echo "<script>alert('Invalid or expired token. Please log in again.'); window.location.href = '" . BASE_URL . "/login.php';</script>";
                exit;
            }

            // Check if the user's role is allowed
            if (!empty($allowed_roles) && !in_array($decoded->role, $allowed_roles)) {
                // Redirect with alert: Access denied
                echo "<script>alert('Access denied'); window.location.href = '" . BASE_URL . "/index.php';</script>";
                exit;
            }

            // Role-based redirection
            if ($decoded->role === 'admin') {
                header("Location: " . BASE_URL . "/app/views/admin/dashboard.php");
                exit();
            } elseif ($decoded->role === 'user') {
                header("Location: " . BASE_URL . "/app/views/user/home.php");
                exit();
            } else {
                header("Location: " . BASE_URL . "/index.php");
                exit();
            }

        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            // Redirect with alert: Invalid or expired token
            echo "<script>alert('Invalid or expired token. Please log in again.'); window.location.href = '" . BASE_URL . "/login.php';</script>";
            exit;
        }
    }

}
