<?php
header("Content-type: text/css");
require 'db.php';

// Fetch colors using MySQLi syntax
$sql = "SELECT name, hex_value FROM colors";
$result = $conn->query($sql);

if ($result) {
    // Use the MySQLi-specific fetch method
    $colors = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($colors as $color) {
        // Sanitize name for CSS class
        $className = str_replace(' ', '-', strtolower($color['name']));
        $hex = $color['hex_value'];

        echo ".color-{$className} { background-color: {$hex}; }\n";
        echo ".text-{$className} { color: {$hex}; }\n";
    }
}
?>