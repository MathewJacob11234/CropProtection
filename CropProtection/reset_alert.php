<?php
$conn = new mysqli("localhost", "root", "", "wildlife_db");
$conn->query("UPDATE alerts SET count = 0 WHERE id=1");
echo "0";
$conn->close();
?>
