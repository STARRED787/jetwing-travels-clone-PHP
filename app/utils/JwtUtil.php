<?php

namespace App\Utils;
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use Exception;

class JwtUtil
{
    private static $secretKey;

    public static function init()
    {
        // Load .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        self::$secretKey = $_ENV['JWT_SECRET'] ?? null;

        if (empty(self::$secretKey) || !is_string(self::$secretKey)) {
            throw new Exception('Secret key is not set or is not a valid string.');
        }
    }

    public static function createToken($userId, $role)
    {
        $payload = [
            'iss' => "blog-site",
            'sub' => $userId,
            'role' => $role, // Add user role
            'iat' => time(),
            'exp' => time() + 3600 // Token expires in 1 hour
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function getSecretKey()
    {
        return self::$secretKey;
    }
}
