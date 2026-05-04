<?php
require_once 'db.php';

if (!$conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $profileId = (int)($_POST['profile_id'] ?? 0);
            portfolio_storage_delete_record($storageFile, $profileId);
        }

        if ($action === 'update') {
            $profileId = (int)($_POST['profile_id'] ?? 0);
            $role = trim($_POST['role'] ?? '');
            $bio = trim($_POST['bio'] ?? '');

            portfolio_storage_update_record($storageFile, $profileId, $role, $bio);
        }
    }

    $rows = portfolio_storage_all_records($storageFile);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $profileId = (int)($_POST['profile_id'] ?? 0);
            $deleteStmt = mysqli_prepare($conn, 'DELETE FROM profiles WHERE id = ?');
            mysqli_stmt_bind_param($deleteStmt, 'i', $profileId);
            mysqli_stmt_execute($deleteStmt);
            mysqli_stmt_close($deleteStmt);
        }

        if ($action === 'update') {
            $profileId = (int)($_POST['profile_id'] ?? 0);
            $role = trim($_POST['role'] ?? '');
            $bio = trim($_POST['bio'] ?? '');

            $updateStmt = mysqli_prepare($conn, 'UPDATE profiles SET role = ?, bio = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateStmt, 'ssi', $role, $bio, $profileId);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);
        }
    }

    $query = '
    SELECT
        p.id AS profile_id,
        u.name,
        u.email,
        p.role,
        p.gender,
        p.interests,
        p.newsletter,
        p.bio,
        p.created_at
    FROM profiles p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
    ';

    $result = mysqli_query($conn, $query);
    $rows = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="panel" style="width:min(1100px,94%);margin:2rem auto;">
        <h2>Submission Dashboard</h2>
        <p>Total records: <?php echo count($rows); ?></p>

        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Gender</th>
                    <th>Interests</th>
                    <th>Newsletter</th>
                    <th>Bio</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) === 0): ?>
                    <tr>
                        <td colspan="10">No records available yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['profile_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                            <td><?php echo htmlspecialchars((string)($row['gender'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars((string)$row['interests']); ?></td>
                            <td><?php echo ((int)$row['newsletter'] === 1) ? 'Yes' : 'No'; ?></td>
                            <td><?php echo htmlspecialchars((string)$row['bio']); ?></td>
                            <td><?php echo htmlspecialchars((string)$row['created_at']); ?></td>
                            <td>
                                <form action="view.php" method="POST" style="margin-bottom:0.4rem;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="profile_id" value="<?php echo (int)$row['profile_id']; ?>">
                                    <button type="submit">Delete</button>
                                </form>

                                <form action="view.php" method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="profile_id" value="<?php echo (int)$row['profile_id']; ?>">
                                    <select name="role" required>
                                        <option value="Frontend Developer">Frontend Developer</option>
                                        <option value="Backend Developer">Backend Developer</option>
                                        <option value="Full Stack Developer">Full Stack Developer</option>
                                    </select>
                                    <textarea name="bio" rows="2" placeholder="Update bio"></textarea>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10">Dashboard supports SELECT, UPDATE, and DELETE. Use form submit page for INSERT.</td>
                </tr>
            </tfoot>
        </table>
        </div>

        <p style="margin-top:1rem;">
            <a class="cta" href="index.html">Back to portfolio form</a>
            <a class="cta" href="hello.php">Hello test page</a>
        </p>
    </main>
</body>
</html>