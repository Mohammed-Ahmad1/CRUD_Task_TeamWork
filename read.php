<?php
require_once 'config.php';

$db = Database::getInstance();
$stmt = $db->query("SELECT * FROM users ORDER BY user_id ASC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    
</head>
<body>
    <h1>All Users</h1>
    
    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div class="actions">
        <a href="create.php">Create New User</a> | 
        <a href="update.php">Update User</a> | 
        <a href="delete.php">Delete User</a>
    </div>
</body>
</html>