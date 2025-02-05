<?php

namespace App\Middleware;

require_once __DIR__ . '/../utils/JwtUtil.php';

use App\Utils\JwtUtil;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

define('BASE_URL', '/KD-Enterprise/jetwing-travels-clone');

class userauthMiddleware
{
    public static function authenticate($allowed_roles = [])
    {
        // Initialize JWT Utility
        JwtUtil::init();
        $secretKey = JwtUtil::getSecretKey(); // Get the secret key

        $token = null; // Initialize token

        // If session token is missing, check Authorization header
        $headers = getallheaders();
        if (!$token && isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (strpos($authHeader, 'Bearer ') === 0) {
                $token = substr($authHeader, 7);
                error_log("✅ Retrieved JWT Token from Authorization header.");
            }
        }

        // If still missing, check JWT token in cookie
        if (!$token && isset($_COOKIE['jwt_token'])) {
            $token = $_COOKIE['jwt_token'];
            error_log("✅ Retrieved JWT Token from Cookie.");
        }

        // If no token is found, redirect to login
        if (!$token) {
            error_log("❌ No JWT Token found. Redirecting to login.");
            echo "<script>alert('Token not provided. Please log in.'); window.location.href = '" . BASE_URL . "/index.php';</script>";
            exit;
        }

        // Validate and handle token
        self::validateToken($token, $allowed_roles, $secretKey);
    }

    /**
     * Validate the JWT token and handle the role-based redirection.
     */
    private static function validateToken($token, $allowed_roles, $secretKey)
    {
        try {
            // Decode the token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            $_SESSION['jwt_token'] = $token; // Refresh session token

            // Role validation
            if (!empty($allowed_roles) && !in_array($decoded->role, $allowed_roles)) {
                error_log("❌ Access denied for role: " . $decoded->role);
                echo "<script>alert('Access denied.'); window.location.href = '" . BASE_URL . "/app/views/admin/dashboard.php';</script>";
                exit;
            }

            error_log("✅ JWT Token successfully validated for user ID: " . $decoded->id);



        } catch (Exception $e) {
            error_log("❌ JWT Decode Error: " . $e->getMessage());
            echo "<script>alert('Invalid or expired token. Please log in again.'); window.location.href = '" . BASE_URL . "/login.php';</script>";
            exit;
        }
    }



}
