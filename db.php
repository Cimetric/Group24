<?php
    $servername = "helmi";
    include "/credentials/user.php";
    include "/credentials/db_name.php";
    include "/credentials/password.php";

    $conn = new mysqli($host, $username, $password, $db);
    
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
?>
