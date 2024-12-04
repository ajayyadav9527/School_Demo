<?php
include 'dbconfig.php';

// Check if the student ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Invalid request: Student ID is missing.");
}

$student_id = $_GET['id'];

// Fetch student data
$student_query = $conn->prepare("SELECT * FROM student WHERE id = ?");
$student_query->bind_param("i", $student_id);
$student_query->execute();
$student_result = $student_query->get_result();

if ($student_result->num_rows == 0) {
    die("Student not found.");
}

$student = $student_result->fetch_assoc();

// Fetch classes for dropdown
$class_result = $conn->query("SELECT * FROM classes");

// Update student data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $student['image']; // Keep the existing image unless updated

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Failed to upload image.");
        }
    }

    // Update student data in the database
    $update_query = $conn->prepare("UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?");
    $update_query->bind_param("sssisi", $name, $email, $address, $class_id, $image, $student_id);

    if ($update_query->execute()) {
        header('Location: index.php'); // Redirect to the main page after update
        exit();
    } else {
        die("Error updating student: " . $update_query->error);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Edit Student</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control"><?php echo htmlspecialchars($student['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    <?php while ($class = $class_result->fetch_assoc()): ?>
                        <option value="<?php echo $class['class_id']; ?>" <?php if ($class['class_id'] == $student['class_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($class['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <img src="uploads/<?php echo htmlspecialchars($student['image']); ?>" width="100" alt="Current Image">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>
