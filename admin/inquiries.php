<?php 
require_once '../config/db.php';
include 'sidebar.php'; 

$inquiries = $conn->query("SELECT * FROM inquiries ORDER BY id DESC");
?>

<h2 class="fw-bold mb-4">Contact Inquiries</h2>

<div class="card border-0 shadow-sm p-4 rounded-3">
    <?php if ($inquiries && $inquiries->num_rows > 0): ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $inquiries->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['message']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted mb-0">No inquiries yet.</p>
    <?php endif; ?>
</div>

</div></div></body></html>