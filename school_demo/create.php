<?php include 'dbconfig.php'; ?>

<?php
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch classes for dropdown
$class_result = $conn->query("SELECT * FROM classes");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $new_class_name = $_POST['new_class_name'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // Validate form inputs
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!empty($image) && !in_array(strtolower(pathinfo($target, PATHINFO_EXTENSION)), ['jpg', 'png'])) {
        $errors[] = "Only JPG and PNG files are allowed.";
    }

    // Handle class selection or creation
    if (empty($class_id) && empty($new_class_name)) {
        $errors[] = "Please select a class or enter a new class name.";
    } elseif (empty($class_id) && !empty($new_class_name)) {
        // Create new class if new_class_name is provided
        $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->bind_param("s", $new_class_name);
        if ($stmt->execute()) {
            $class_id = $stmt->insert_id;
        } else {
            $errors[] = "Error creating new class: " . $stmt->error;
        }
        $stmt->close();
    }

    if (empty($errors)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $name, $email, $address, $class_id, $image);

            if ($stmt->execute()) {
                header('Location: index.php');
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Create Student</h1>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    <option value="">Select an existing class</option>
                    <?php while ($class = $class_result->fetch_assoc()): ?>
                        <option value="<?php echo $class['class_id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="text" name="new_class_name" class="form-control mt-2" placeholder="Or enter a new class name">
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
