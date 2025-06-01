<?php
require_once "../config.php";
include "../header.php";

// Join receipts with student names
$sql = "SELECT r.*, s.first_name, s.last_name, s.class_level 
        FROM receipts r
        JOIN students s ON r.student_id = s.student_id
        ORDER BY r.issued_date DESC";

$result = $conn->query($sql);
?>

<h2 class="mt-4">All Receipts</h2>

<a href="new_receipt.php" class="btn btn-primary mb-3">+ New Receipt</a>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Amount (GHS)</th>
                    <th>Description</th>
                    <th>Issued By</th>
                    <th>Date</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; ?>
                <?php while ($r = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($r['last_name'] . ", " . $r['first_name']) ?></td>
                        <td><?= $r['class_level'] ?></td>
                        <td><?= number_format($r['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($r['description']) ?></td>
                        <td><?= htmlspecialchars($r['issued_by']) ?></td>
                        <td><?= $r['issued_date'] ?></td>
                        <td>
                            <a href="generate_receipt.php?id=<?= $r['receipt_id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Print</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No receipts found.</div>
<?php endif; ?>

<?php include "../footer.php"; ?>