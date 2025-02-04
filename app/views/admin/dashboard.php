<?php

// Include the necessary middleware for authentication
require_once __DIR__ . '/../../middleware/authMiddleware.php';

use App\Middleware\AuthMiddleware;

// Authenticate the user before accessing the admin dashboard
AuthMiddleware::authenticate(['admin']);  // Only allow 'admin' role to access the dashboard

// Your admin dashboard content goes here
echo "Welcome, Admin! This is your dashboard.";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center">Manage Blogs</h2>

        <!-- Create Post Modal -->
        <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPostLabel">Create New Blog Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createPostForm">
                            <div class="mb-3">
                                <label for="postTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="postTitle" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="postContent" class="form-label">Content</label>
                                <textarea class="form-control" id="postContent" name="content" rows="4"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Create Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button to Open Modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPostModal">
            New Post
        </button>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Data (Replace with PHP loop for dynamic data) -->
                <tr>
                    <td>1</td>
                    <td>Blog Title Example</td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal"
                            data-bs-target="#editModal" data-id="1" data-title="Blog Title Example">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Edit Blog Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>