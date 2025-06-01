<?php
require_once "../config.php";
include "../header.php";

$students = $conn->query("SELECT student_id, first_name, last_name, class_level FROM students ORDER BY last_name");
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = (int) $_POST['student_id'];
    $amount = (float) $_POST['amount'];
    $description = trim($_POST['description']);
    $issued_by = trim($_POST['issued_by']);

    $stmt = $conn->prepare("INSERT INTO receipts (student_id, amount, description, issued_by, issued_date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("idss", $student_id, $amount, $description, $issued_by);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Receipt saved successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to save receipt.</div>";
    }
}
?>

<h2 class="mt-4">Issue New Receipt</h2>
<?= $message ?>

<form method="POST" class="row g-3 mt-2">
    <div class="col-md-6">
        <label class="form-label">Select Student</label>
        <select name="student_id" class="form-select" required>
            <option value="">-- Select --</option>
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['student_id'] ?>">
                    <?= htmlspecialchars($s['last_name'] . ", " . $s['first_name']) ?> (<?= $s['class_level'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Amount</label>
        <input type="number" name="amount" class="form-control" step="0.01" required>
    </div>

    <div class="col-md-12">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control" placeholder="e.g. School Fees Term 1" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Issued By</label>
        <input type="text" name="issued_by" class="form-control" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">Save Receipt</button>
        <a href="list_receipts.php" class="btn btn-secondary">Back to Receipts</a>
    </div>
</form>

<?php include "../footer.php"; ?>