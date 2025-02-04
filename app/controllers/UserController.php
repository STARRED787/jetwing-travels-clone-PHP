<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/JwtUtil.php';  // Ensure JWT utility is imported
require_once __DIR__ . '/../middleware/authMiddleware.php';  // Ensure JWT utility is imported

use App\Models\User;
use App\Utils\JwtUtil;  // JWT utility class
use Exception;
use App\Middleware\AuthMiddleware;  // Import the AuthMiddleware


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
                $password = $_POST['password'] ?? ''; // Raw password, don't hash here

                if (empty($username) || empty($password)) {
                    throw new Exception("Username and password are required.");
                }

                // Debug
                error_log("Registration attempt - Username: " . $username);
                error_log("Registration - Password length: " . strlen($password));

                // Let the model handle the hashing
                $result = $this->userModel->createUser($username, $password, 'user');

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
                $password = $_POST['password'] ?? ''; // Raw password

                error_log("Login attempt - Username: " . $username);
                error_log("Login - Password length: " . strlen($password));

                $user = $this->userModel->getUserByUsername($username);

                if (!$user) {
                    throw new Exception("User not found.");
                }

                // Direct password verification
                if (!password_verify($password, $user['password'])) {
                    error_log("Password verification failed for user: " . $username);
                    throw new Exception("Invalid password.");
                }

                // If we get here, password is correct
                error_log("Password verified successfully for user: " . $username);

                // JWT Token creation
                $token = JwtUtil::createToken($user['id'], $user['role']);
                if (!$token) {
                    throw new Exception("Failed to create token.");
                }

                // Set JWT token in a secure cookie
                $cookieResult = setcookie('jwt_token', $token, [
                    'expires' => time() + 3600,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                if (!$cookieResult) {
                    throw new Exception("Failed to set JWT cookie.");
                }

                // Authenticate and redirect
                AuthMiddleware::authenticate();
                header('Location: ' . BASE_URL . '/dashboard.php');
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