<?php
// Configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "crop_protection";

// Step 1: Connect to MySQL (no DB selected yet)
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Create database if not exists
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sqlCreateDB);

// Step 3: Select the database
$conn->select_db($dbname);

// Step 4: Create table if not exists
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS sighting (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(255),
    species VARCHAR(255),
    count INT,
    image VARCHAR(255),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sqlCreateTable);

// Step 5: Handle form POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location']; 
    $species = $_POST['species'];
    $count = $_POST['count'];
    $image = $_FILES['image']['name'];

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // create folder if not exist
    }

    $targetFile = $targetDir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $sqlInsert = "INSERT INTO sighting (location, species, count, image) 
                      VALUES ('$location', '$species', '$count', '$image')";
        
        if ($conn->query($sqlInsert) === TRUE) {
            $last_id = $conn->insert_id;
            $result = $conn->query("SELECT * FROM sighting WHERE id = $last_id");
            $newSighting = $result->fetch_assoc();

            echo json_encode(["success" => true, "message" => "Sighting added!", "sighting" => $newSighting]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed."]);
    }
}
// Step 6: If GET request, fetch all sightings
else {
    $sql = "SELECT * FROM sighting ORDER BY timestamp DESC";
    $result = $conn->query($sql);

    $sightings = [];
    while ($row = $result->fetch_assoc()) {
        $sightings[] = $row;
    }

    echo json_encode($sightings);
}

// Close connection
$conn->close();
?>
