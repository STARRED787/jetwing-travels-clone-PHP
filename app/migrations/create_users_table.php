<?php

require_once __DIR__ . '/../config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {
    $table->id();
    $table->string('username')->unique();
    $table->string('password');
    $table->string('jwt_token')->nullable();
    $table->enum('role', ['user', 'admin'])->default('user'); // Allow only 'user' or 'admin'
    $table->timestamps();

});

echo "Users table created successfully!";
?>
