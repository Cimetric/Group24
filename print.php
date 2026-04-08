<?php
$size = $_POST['size'] ?? 26;
$numColors = $_POST['numColors'] ?? 10;
$selectedColors = $_POST['selectedColors'] ?? ['Red','Orange','Yellow','Green','Blue','Purple','Grey','Brown','Black','Teal'];

$postError = false;

if($size < 1 || $size > 26) $postError = true;
if($numColors < 1 || $numColors > 10) $postError = true;
if(count($selectedColors) !== $numColors) $postError = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Group 24">
    <title>Print</title>
    <link rel="stylesheet" href="print.css">
</head>
<body>
    <header> 
        <div class="logo-area">
            <img src="images/teamlogo.png" alt="404 Logo" class="header-logo" width="200">
            <span class="logo-main"><h1 style="display: inline;">404: Team Not Found</h1></span>
        </div>
    </header>
    <div class = "Table 1">
        <table class = "color-table">
        <?php foreach($selectedColors as $color) {
            echo '<tr>';
            echo '<td style="width: 20%;">' . $color . '</td>';
            echo '<td style="background-color: ' . $color . '; width: 80%;">' . $color . '</td>';
            echo "</tr>";
        }
        ?>
        </table>
    </div>
    <div class = "Table 2">
        <table class="grid-table">
            <?php for ($r = 0; $r <= $size; $r++): ?>
            <tr>
                <?php for ($c = 0; $c <= $size; $c++): ?>
                <?php if ($r === 0 && $c === 0): ?>
                    <td></td>
                <?php elseif ($r === 0): ?>
                    <td><?php echo chr(64 + $c); ?></td>
                <?php elseif ($c === 0): ?>
                    <td><?php echo $r; ?></td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>
    </div>
</body>
</html>