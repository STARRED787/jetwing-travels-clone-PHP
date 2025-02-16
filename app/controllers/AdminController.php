<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/JwtUtil.php';
require_once __DIR__ . '/../middleware/adminauthMiddleware.php';

use App\Models\User;
use App\Utils\JwtUtil;
use App\Middleware\AuthMiddleware;
use Exception;

class AdminController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        JwtUtil::init();
    }

    // Register User
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = $this->validateInput($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($username) || empty($password)) {
                    throw new Exception("Username and password are required.");
                }

                $result = $this->userModel->createUser($username, $password, 'user');

                if ($result) {
                    echo "<script>
                        alert('Registration successful! Redirecting to login page...');
                        window.location.href = '" . BASE_URL . "/app/views/admin/signin.php';
                    </script>";
                    exit();
                } else {
                    throw new Exception("User registration failed.");
                }
            } catch (Exception $e) {
                error_log("Registration error: " . $e->getMessage());
                echo "<script>
                    alert('Error: " . $this->getErrorMessage($e->getMessage()) . "' );
                    window.location.href = '" . BASE_URL . "/app/views/admin/signin.php';
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
                $password = $_POST['password'] ?? '';

                $user = $this->userModel->getUserByUsername($username);

                if (!$user) {
                    throw new Exception("User not found.");
                }

                if (!password_verify($password, $user['password'])) {
                    throw new Exception("Invalid password.");
                }

                // Generate JWT Token
                $token = JwtUtil::createToken($user['id'], $user['role']);
                if (!$token) {
                    throw new Exception("Failed to create token.");
                } else {
                    error_log("Successfully created token: " . $token);

                    // Store the token in a cookie (optional)
                    setcookie('jwt_token', $token, time() + 3600, "/", "", true, true); // Secure flag for HTTPS

                    // After successful login, redirect to the dashboard
                    if ($user['role'] === 'admin') {
                        echo "<script>
                        alert('Login successful! Redirecting Admin Dashboard...');
                        window.location.href = '" . BASE_URL . "/app/views/admin/dashboard.php';
                      </script> ";
                    } else {
                        echo "<script>
                        alert('Login unsuccessful! User role is.....: " . $user['role'] . "');
                        window.location.href = '" . BASE_URL . "/app/views/admin/signin.php';
                    </script>";
                    }
                    exit();
                }
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                echo "<script>
                    alert('Error: " . $this->getErrorMessage($e->getMessage()) . "' );
                    window.location.href = '" . BASE_URL . "/app/views/admin/signin.php';
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
