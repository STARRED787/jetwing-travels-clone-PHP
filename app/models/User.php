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
        // Ensure database is initialized
        $this->setConnection('default');
    }

    // New method to get user by username
    public static function getUserByUsername($username)
    {
        try {
            // Using Eloquent to find user by username
            return self::where('username', $username)->first(); // Returns the first match or null
        } catch (\Exception $e) {
            // Log error if there's any issue
            error_log("Error fetching user by username: " . $e->getMessage());
            return null; // Return null if there's an error
        }
    }

    public function createUser($username, $password, $role = 'user')
    {
        try {
            return DB::connection()->transaction(function () use ($username, $password, $role) {
                // Check if username already exists
                if (self::where('username', $username)->exists()) {
                    throw new \Exception('Username already exists');
                }

                return self::create([
                    'username' => $username,
                    'password' => $password,
                    'role' => $role
                ]);
            });
        } catch (\Exception $e) {
            // Add error logging for debugging
            error_log("Error creating user: " . $e->getMessage());
            throw $e; // Re-throw to be caught by controller
        }
    }
}
