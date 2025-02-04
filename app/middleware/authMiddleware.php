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
            // Initialize JWT secret key
            JwtUtil::init();
            $secretKey = JwtUtil::getSecretKey();

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
            }

            if (!$token) {
                // Redirect with alert: Token not provided
                echo "<script>alert('Token not provided'); window.location.href ='" . BASE_URL . "/index.php';</script>";
                exit;
            }

            // Decode JWT Token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Check if the user's role is allowed
            if (!empty($allowed_roles) && !in_array($decoded->role, $allowed_roles)) {
                // Redirect with alert: Access denied
                echo "<script>alert('Access denied'); window.location.href = '" . BASE_URL . "/index.php';</script>";
                exit;
            }

            return $decoded; // Token is valid, return user data

        } catch (Exception $e) {
            // Redirect with alert: Invalid or expired token
            echo "<script>alert('Invalid or expired token'); window.location.href = '" . BASE_URL . "/index.php';</script>";
            exit;
        }
    }
}
