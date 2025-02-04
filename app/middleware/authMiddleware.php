<?php

namespace App\Middleware;

require_once __DIR__ . '/../utils/JwtUtil.php';

use App\Utils\JwtUtil;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

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
                header("HTTP/1.1 401 Unauthorized");
                echo json_encode(["error" => "Token not provided"]);
                exit;
            }

            // Decode JWT Token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Check if the user's role is allowed
            if (!empty($allowed_roles) && !in_array($decoded->role, $allowed_roles)) {
                header("HTTP/1.1 403 Forbidden");
                echo json_encode(["error" => "Access denied"]);
                exit;
            }

            return $decoded; // Token is valid, return user data

        } catch (Exception $e) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "Invalid or expired token", "message" => $e->getMessage()]);
            exit;
        }
    }
}
