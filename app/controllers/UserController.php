<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/JwtUtil.php';  // Ensure JWT utility is imported

use App\Models\User;
use App\Utils\JwtUtil;  // JWT utility class
use Exception;

define('BASE_URL', '/KD-Enterprise/blog-site');

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        JwtUtil::init(); // Ensure secret key is initialized before JWT operations
    }

    // Register User
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = $this->validateInput($_POST['username'] ?? '');
                $password = $this->validateInput($_POST['password'] ?? '');

                if (empty($username) || empty($password)) {
                    throw new Exception("Username and password are required.");
                }

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $result = $this->userModel->createUser($username, $hashedPassword, 'user');

                if ($result) {
                    echo "<script>
                        alert('Registration successful! Redirecting to login page...');
                        window.location.href = '" . BASE_URL . "/index.php';
                    </script>";
                    exit();
                } else {
                    throw new Exception("User registration failed.");
                }
            } catch (Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                echo "<script>
                    alert('Error: " . $this->getErrorMessage($e->getMessage()) . "');
                    window.location.href = '" . BASE_URL . "/index.php';
                </script>";
            }
        }
    }

    // Login User
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = $this->validateInput($_POST['username'] ?? '');
                $password = $this->validateInput($_POST['password'] ?? '');

                if (empty($username) || empty($password)) {
                    throw new Exception("Username and password are required.");
                }

                $user = $this->userModel->getUserByUsername($username);
                if (!$user) {
                    throw new Exception("User not found.");
                }

                if (!password_verify($password, $user['password'])) {
                    throw new Exception("Invalid password.");
                }


                // Generate JWT token with role
                $token = JwtUtil::createToken($user['id'], $user['role']);


                // Set JWT token as a secure HTTP-only cookie
                setcookie('jwt_token', $token, [
                    'expires' => time() + 3600,
                    'path' => '/',
                    'secure' => true,  // Use HTTPS in production
                    'httponly' => true, // Prevent JavaScript access
                    'samesite' => 'Strict' // CSRF protection
                ]);

                echo "<script>
                    alert('Login successful! Redirecting...');
                    window.location.href = '" . BASE_URL . "/app/views/admin/dashboard.php';
                </script>";
                exit();
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                echo "<script>
                    alert('Error: " . $this->getErrorMessage($e->getMessage()) . "');
                    window.location.href = '" . BASE_URL . "/index.php';
                </script>";
            }
        }
    }

    private function validateInput($input)
    {
        return htmlspecialchars(trim($input));
    }

    private function getErrorMessage($error)
    {
        if (strpos($error, 'User not found') !== false) {
            return 'The username does not exist.';
        } elseif (strpos($error, 'Invalid password') !== false) {
            return 'Incorrect password.';
        } elseif (strpos($error, 'Username and password are required') !== false) {
            return 'Please enter both username and password.';
        } else {
            return 'An error occurred. Please try again.';
        }
    }
}
?>