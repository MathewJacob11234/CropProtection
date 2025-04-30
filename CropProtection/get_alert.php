<?php
$conn = new mysqli("localhost", "root", "", "wildlife_db");
$result = $conn->query("SELECT count FROM alerts WHERE id=1");
$row = $result->fetch_assoc();
echo $row["count"];
$conn->close();
?>
