<?php
require_once "../config.php";
include "../header.php";

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $class_level = trim($_POST['class_level']);

    if ($first_name && $last_name && $dob && $gender && $class_level) {
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, date_of_birth, gender, class_level, admission_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $first_name, $last_name, $dob, $gender, $class_level);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Student added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error saving student.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all fields.</div>";
    }
}
?>

<h2 class="mt-4">Add New Student</h2>
<?= $message ?>

<form method="POST" action="" class="row g-3 mt-2">
    <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="class_level" class="form-label">Class Level</label>
        <input type="text" name="class_level" class="form-control" placeholder="e.g. Basic 1" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">Add Student</button>
        <a href="list_students.php" class="btn btn-secondary">Back to List</a>
    </div>
</form>

<?php include "../footer.php"; ?>
