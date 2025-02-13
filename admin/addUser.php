<?php
// Include the database connection
include('db.php');

// Handle the add user action (if add user form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Handle the image upload
    $image = 'default.jpg'; // Default image
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['profileImage']['tmp_name'];
        $imageName = uniqid() . '-' . $_FILES['profileImage']['name'];
        $imageUploadPath = 'uploads/' . $imageName;
        if (move_uploaded_file($imageTmpPath, $imageUploadPath)) {
            $image = $imageName;
        }
    }

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO tbl_users (name, email, password, role, image) VALUES (:name, :email, :password, :role, :image)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':image', $image);
    $stmt->execute();

    // Reload the page to reflect changes
    header('Location: dashboard.php');
    exit();
}
?>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="addUser.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 text-center">
                        <label for="profileImage" class="form-label">Profile Image</label>
                        <div>
                            <img id="profileImagePreview" src="uploads/default.jpg" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('profileImage').click();">
                        </div>
                        <input type="file" class="form-control mt-2" id="profileImage" name="profileImage" accept="image/*" onchange="previewImage(event)" style="display: none;">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="Coach">Coach</option>
                            <option value="Premium Membership">Premium Membership</option>
                            <option value="Basic Membership">Basic Membership</option>
                            <option value="Standard Membership">Standard Membership</option>
                        </select>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>