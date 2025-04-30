<?php
// post_forum_message.php

header('Content-Type: application/json'); // Set response type to JSON

// Database connection details
$host = 'localhost'; // Replace with your database host
$dbname = 'wildlife'; // Your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $input = json_decode(file_get_contents('php://input'), true);

    // Extract data from the input
    $firstName = $input['firstName'] ?? '';
    $lastName = $input['lastName'] ?? '';
    $address = $input['address'] ?? '';
    $message = $input['message'] ?? '';

    // Validate the data
    if (empty($firstName) || empty($lastName) || empty($address) || empty($message)) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    try {
        // Connect to the MySQL database using PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL query to insert data
        $stmt = $pdo->prepare("
            INSERT INTO forum(first_name, last_name, address, message)
            VALUES (:first_name, :last_name, :address, :message)
        ");

        // Bind the input data to the query parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':message', $message);

        // Execute the query
        $stmt->execute();

        // Return a success response
        http_response_code(200); // OK
        echo json_encode(['success' => 'Message posted successfully']);
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500); // Internal server error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method not allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>