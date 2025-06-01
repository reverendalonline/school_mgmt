<?php
require_once "../config.php";
include "../header.php";

// Load student list
$students = $conn->query("SELECT id, full_name FROM students ORDER BY full_name ASC");

// Save comment
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $term = $_POST['term'];
    $conduct = $_POST['conduct'];
    $interest = $_POST['interest'];
    $teacher = $_POST['teacher_comment'];
    $head = $_POST['head_comment'];

    // Insert or update comment
    $stmt = $conn->prepare("INSERT INTO report_comments (student_id, term, conduct, interest, teacher_comment, head_comment) 
                            VALUES (?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            conduct = VALUES(conduct), interest = VALUES(interest), 
                            teacher_comment = VALUES(teacher_comment), head_comment = VALUES(head_comment)");

    $stmt->bind_param("isssss", $student_id, $term, $conduct, $interest, $teacher, $head);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Comment saved.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error saving comment.</div>";
    }
}
?>

<div class="container-fluid">
    <h2 class="mb-4">Enter Report Comments</h2>
    <?= $message ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Select Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Choose Student --</option>
                <?php while($stu = $students->fetch_assoc()): ?>
                    <option value="<?= $stu['id'] ?>"><?= htmlspecialchars($stu['full_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Term</label>
            <select name="term" class="form-select" required>
                <option value="Term 1">Term 1</option>
                <option value="Term 2">Term 2</option>
                <option value="Term 3">Term 3</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Conduct</label>
            <textarea name="conduct" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Interest</label>
            <textarea name="interest" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Teacher's Comment</label>
            <textarea name="teacher_comment" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Headteacher's Remark</label>
            <textarea name="head_comment" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Comment</button>
    </form>
</div>

<?php include "../footer.php"; ?>
