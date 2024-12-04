<?php
// Database connection
// $conn = new mysqli('localhost', 'root', 'password', 'school_db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $sql = "INSERT INTO classes (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        header('Location: classes.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$class_result = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Classes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Classes</h1>
        <form method="post">
            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Class</button>
        </form>
        <h2>Class List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($class = $class_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $class['name']; ?></td>
                    <td>
                        <a href="edit_class.php?id=<?php echo $class['class_id']; ?>">Edit</a>
                        <a href="delete_class.php?id=<?php echo $class['class_id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
