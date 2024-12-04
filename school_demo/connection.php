<?php include 'dbconfig.php' ?>
<?php

if ($con) {
    echo "Connection Successful<br>";

    // SQL query to create the 'classes' table
    $createClassesTable = "
        CREATE TABLE IF NOT EXISTS classes (
            class_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";

    // SQL query to create the 'students' table
    $createStudentsTable = "
        CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            class_id INT,
            image VARCHAR(255),
            FOREIGN KEY (class_id) REFERENCES classes(class_id)
        );
    ";

    // Execute the queries
    if (mysqli_query($con, $createClassesTable)) {
        echo "Classes table created successfully<br>";
    } else {
        echo "Error creating Classes table: " . mysqli_error($con) . "<br>";
    }

    if (mysqli_query($con, $createStudentsTable)) {
        echo "Students table created successfully<br>";
    } else {
        echo "Error creating Students table: " . mysqli_error($con) . "<br>";
    }
} else {
    echo "Connection Failed: " . mysqli_connect_error();
}

// Close the connection
mysqli_close($con);
?>
