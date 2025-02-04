<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'role', 'jwt_token'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('default');
    }

    public static function getUserByUsername($username)
    {
        try {
            $user = self::where('username', $username)->first();
            if (!$user) {
                error_log("No user found with username: " . $username);
                return null;
            }

            // Convert to array and ensure password is included
            $userData = $user->toArray();
            error_log("Retrieved user data for: " . $username);

            return $userData;
        } catch (\Exception $e) {
            error_log("Error fetching user by username: " . $e->getMessage());
            return null;
        }
    }

    public function createUser($username, $plainPassword, $role = 'user')
    {

        try {
            return DB::transaction(function () use ($username, $plainPassword, $role) {
                // Check if username exists
                if (self::where('username', $username)->exists()) {
                    throw new \Exception('Username already exists');
                }

                // Hash the password
                $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

                // Debug
                error_log("Creating user - Username: " . $username);
                error_log("Creating user - Original password length: " . strlen($plainPassword));
                error_log("Creating user - Hash length: " . strlen($hashedPassword));

                // Create the user
                $user = self::create([
                    'username' => $username,
                    'password' => $hashedPassword,
                    'role' => $role
                ]);

                if (!$user) {
                    throw new \Exception('Failed to create user');
                }

                return $user;
            });
        } catch (\Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }

    // Helper method to verify password
    public static function verifyPassword($plainPassword, $hashedPassword)
    {
        error_log("Verifying password - Length of plain password: " . strlen($plainPassword));
        error_log("Verifying against hash: " . substr($hashedPassword, 0, 10) . "...");

        $result = password_verify($plainPassword, $hashedPassword);
        error_log("Password verification result: " . ($result ? 'true' : 'false'));

        return $result;
    }

    public function updateUserToken($userId, $jwtToken)
    {
        try {
            $user = self::find($userId);
            if ($user) {
                if ($user->jwt_token !== $jwtToken) {
                    $user->jwt_token = $jwtToken;
                    $user->save();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            error_log("Error updating JWT token: " . $e->getMessage());
            return false;
        }
    }
}
