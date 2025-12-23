<?php
require_once 'config.php';

$message = '';
if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    
    if (!empty($name)) {
        try {
            $db = Database::getInstance();
            $sql = "INSERT INTO users (username) VALUES (?)";
            $stmt = $db->query($sql, [$name]);
            
            if ($stmt->rowCount() > 0) {
                $message = "<p style='color:green;'>User created successfully!</p>";
            }
        } catch (Exception $e) {
            $message = "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color:red;'>Please enter a name.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>

</head>
<body>
    <h1>Create User</h1>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    
    <form method="post">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" required>
        </div>
        <button type="submit" name="submit">Create User</button>
    </form>
    
    <br>
    <a href="read.php">View All Users</a> | 
    <a href="update.php">Update User</a> | 
    <a href="delete.php">Delete User</a>
</body>
</html>