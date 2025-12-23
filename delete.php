<?php
require_once 'config.php';

$message = '';
$users = [];

// Fetch all users for dropdown
$db = Database::getInstance();
$stmt = $db->query("SELECT * FROM users ORDER BY username ASC");
$users = $stmt->fetchAll();

if (isset($_POST['delete'])) {
    $user_id = (int)$_POST['user_id'];
    
    if ($user_id > 0) {
        try {
            $db = Database::getInstance();
            
            // Start transaction
            $db->beginTransaction();
            
            // Delete related orders first
            $sql = "DELETE FROM orders WHERE user_id = ?";
            $db->query($sql, [$user_id]);
            
            // Then delete the user
            $sql = "DELETE FROM users WHERE user_id = ?";
            $stmt = $db->query($sql, [$user_id]);
            
            if ($stmt->rowCount() > 0) {
                $db->commit();
                $message = "<p style='color:green;'>User deleted successfully!</p>";
                // Refresh users list after deletion
                $stmt = $db->query("SELECT * FROM users ORDER BY username ASC");
                $users = $stmt->fetchAll();
            } else {
                $db->rollBack();
                $message = "<p style='color:orange;'>User not found.</p>";
            }
        } catch (Exception $e) {
            $db->rollBack();
            $message = "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color:red;'>Invalid user ID.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
    
</head>
<body>
    <h1>Delete User</h1>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?')">
        <div class="form-group">
            <label>Select User to Delete:</label>
            <select name="user_id" required>
                <option value="">-- Choose a user --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>">
                        <?php echo htmlspecialchars($user['user_id']) . ' - ' . htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" name="delete">Delete User</button>
    </form>
    
    <br>
    <a href="create.php">Create New User</a> | 
    <a href="read.php">View All Users</a> | 
    <a href="update.php">Update User</a>
</body>
</html>