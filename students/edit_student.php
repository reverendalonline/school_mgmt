<?php
require_once "../config.php";
include "../header.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid student ID.</div>";
    include "../footer.php";
    exit;
}

$student_id = (int) $_GET['id'];
$message = "";

// Load student info
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Student not found.</div>";
    include "../footer.php";
    exit;
}

$student = $result->fetch_assoc();

// Handle update form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $class_level = trim($_POST['class_level']);

    if ($first_name && $last_name && $dob && $gender && $class_level) {
        $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, date_of_birth = ?, gender = ?, class_level = ? WHERE student_id = ?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $dob, $gender, $class_level, $student_id);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Student updated successfully.</div>";
            $student = array_merge($student, [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'class_level' => $class_level
            ]);
        } else {
            $message = "<div class='alert alert-danger'>Update failed.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all fields.</div>";
    }
}
?>

<h2 class="mt-4">Edit Student</h2>
<?= $message ?>

<form method="POST" action="" class="row g-3 mt-2">
    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($student['first_name']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($student['last_name']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($student['date_of_birth']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="Male" <?= $student['gender'] == "Male" ? "selected" : "" ?>>Male</option>
            <option value="Female" <?= $student['gender'] == "Female" ? "selected" : "" ?>>Female</option>
            <option value="Other" <?= $student['gender'] == "Other" ? "selected" : "" ?>>Other</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Class Level</label>
        <input type="text" name="class_level" class="form-control" value="<?= htmlspecialchars($student['class_level']) ?>" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="student_details.php?id=<?= $student['student_id'] ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php include "../footer.php"; ?>
