<?php
require_once 'config.php';

$message = '';
$users = [];

// Fetch all users for dropdown
$db = Database::getInstance();
$stmt = $db->query("SELECT * FROM users ORDER BY username ASC");
$users = $stmt->fetchAll();

if (isset($_POST['update'])) {
    $user_id = (int)$_POST['user_id'];
    $name = trim($_POST['name'] ?? '');
    
    if (!empty($name) && $user_id > 0) {
        try {
            $db = Database::getInstance();
            $sql = "UPDATE users SET username = ? WHERE user_id = ?";
            $stmt = $db->query($sql, [$name, $user_id]);
            
            if ($stmt->rowCount() > 0) {
                $message = "<p style='color:green;'>User updated successfully!</p>";
                // Refresh users list after update
                $stmt = $db->query("SELECT * FROM users ORDER BY username ASC");
                $users = $stmt->fetchAll();
            } else {
                $message = "<p style='color:orange;'>User not found.</p>";
            }
        } catch (Exception $e) {
            $message = "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color:red;'>Invalid input.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    
</head>
<body>
    <h1>Update User</h1>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    
    <form method="post">
        <div>
            <label>Select User:</label>
            <select name="user_id" required onchange="fillName(this)">
                <option value="">-- Choose a user --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>" 
                            data-name="<?php echo htmlspecialchars($user['username']); ?>">
                        <?php echo htmlspecialchars($user['user_id']) . ' - ' . htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label>New Name:</label>
            <input type="text" name="name" id="nameInput" required>
        </div>
        
        <button type="submit" name="update">Update User</button>
    </form>
    
    <script>
        function fillName(select) {
            var selectedOption = select.options[select.selectedIndex];
            var name = selectedOption.getAttribute('data-name');
            document.getElementById('nameInput').value = name || '';
        }
    </script>
    
    <br>
    <a href="create.php">Create New User</a> | 
    <a href="read.php">View All Users</a> | 
    <a href="delete.php">Delete User</a>
</body>
</html>