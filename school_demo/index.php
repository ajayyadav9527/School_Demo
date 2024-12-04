
<?php include 'dbconfig.php' ?>
<?php
// Database connection
//$conn = new mysqli('localhost', 'username', 'password', 'school_db');

// Fetch students with class names
$sql = "SELECT student.id, student.name, student.email, student.created_at, classes.name AS class_name, student.image 
        FROM student 
        JOIN classes ON student.class_id = classes.class_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Student List</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Class</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php echo $row['class_name']; ?></td>
                    <td><img src="uploads/<?php echo $row['image']; ?>" width="50"></td>
                    <td>
                        <a href="create.php">Create</a>
                        <a href="view.php?id=<?php echo $row['id']; ?>">View</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
