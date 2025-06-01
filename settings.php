<h2 class="mt-4">School Settings</h2>
<?= $message ?>

<div class="row mt-3">
    <!-- Settings form (left side) -->
    <div class="col-md-8">
        <form action="" method="POST" enctype="multipart/form-data" class="row g-3">

            <div class="col-md-12">
                <label class="form-label">School Name</label>
                <input type="text" name="school_name" class="form-control" value="<?= htmlspecialchars($row['school_name']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address_line1" class="form-control" value="<?= htmlspecialchars($row['address_line1']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Address Line 2</label>
                <input type="text" name="address_line2" class="form-control" value="<?= htmlspecialchars($row['address_line2']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($row['city']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">State/Region</label>
                <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($row['state']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Zip Code</label>
                <input type="text" name="zipcode" class="form-control" value="<?= htmlspecialchars($row['zipcode']) ?>">
            </div>

            <div class="col-md-12">
                <label class="form-label">Upload New Logo (optional)</label>
                <input type="file" name="logo" class="form-control">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>

    <!-- Logo preview (right side) -->
    <div class="col-md-4 text-center">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Current Logo</h6>
                <?php if (!empty($row['logo_filename'])): ?>
                    <img src="images/<?= htmlspecialchars($row['logo_filename']) ?>" alt="Logo" class="img-fluid" style="max-height: 100px;">
                <?php else: ?>
                    <p class="text-muted">No logo uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
