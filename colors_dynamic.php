<?php
// Set the content type so the browser treats this as CSS, not HTML
header("Content-type: text/css");

require 'db.php'; // Access your database connection

try {
    // Fetch all color names and hex values
    $stmt = $conn->query("SELECT name, hex_value FROM colors");
    $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($colors as $color) {
        // Sanitize the name to ensure it's a valid CSS class (e.g., remove spaces)
        $className = str_replace(' ', '-', strtolower($color['name']));
        $hex = $color['hex_value'];

        // Output the CSS rule
        echo ".color-{$className} { background-color: {$hex}; }\n";
        echo ".text-{$className} { color: {$hex}; }\n";
    }
} catch (PDOException $e) {
    // Silently fail or log error so it doesn't break the CSS file
}
?>