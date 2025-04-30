<?php
$conn = new mysqli("localhost", "root", "", "wildlife_db");
$conn->query("UPDATE alerts SET count = count + 1 WHERE id=1");
$result = $conn->query("SELECT count FROM alerts WHERE id=1");
$row = $result->fetch_assoc();
echo $row["count"];
$conn->close();
?>
