<?php
    $servername = "helmi";
    include __DIR__ . "/credentials/user.php";
    include __DIR__ . "/credentials/db_name.php";
    include __DIR__ . "/credentials/password.php";

    $conn = new mysqli($servername, $username, $password, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
