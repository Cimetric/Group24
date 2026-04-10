<?php
$rows = isset($_POST['rows']) ? (int)$_POST['rows'] : null;
$numColors = isset($_POST['numColors']) ? (int)$_POST['numColors'] : null;

$errors = [];

if ($rows < 1 || $rows > 26) {
        $errors[] = "Rows and Columns must be between 1 and 26.";
}
if ($numColors < 1 || $numColors > 10) {
        $errors[] = "Number of Colors must be between 1 and 10.";
}

$valid = empty($errors);

$allColors = ['Red','Orange','Yellow','Green','Blue','Purple','Grey','Brown','Black','Teal'];

$colorHex = [
    'Red'    => '#FF0000',
    'Orange' => '#FFA500',
    'Yellow' => '#FFFF00',
    'Green'  => '#008000',
    'Blue'   => '#0000FF',
    'Purple' => '#800080',
    'Grey'   => '#808080',
    'Brown'  => '#8B4513',
    'Black'  => '#000000',
    'Teal'   => '#008080',
];
$selectedColors = [];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Color | 404: Team Not Found</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="logo-area">
                <span class="logo-main">404:</span><span class="logo-sub"> Team Not Found</span>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li class="active"><a href="color.php">Color Coordinator</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <form method="POST" action="color.php">
            <label for="rows"> Rows and Columns (1-26): </label> 
            <input type="number" name="rows" id="rows" min="1" max="26" value="<?php echo isset($rows) ? $rows : ''; ?>"> 
            <label for="numColors"> Number of Colors (1-10): </label>
            <input type="number" name="numColors" id="numColors" min="1" max="10" value="<?php echo isset($numColors) ? $numColors : ''; ?>"> 
            <button type="submit"> Generate </button>
        </form> 

        <?php foreach ($errors as $err): ?>
        <p class="error"><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>

        <p id="color-msg" class="color-message"></p>

        <table class="color-table">
            <?php for ($i = 0; $i < $numColors; $i++): ?>
            <tr>
                <td style="width:20%;">
                    <select class="color-dropdown" data-index="<?php echo $i; ?>">
                        <?php foreach ($allColors as $color): ?>
                            <option value="<?php echo $color; ?>"
                                <?php if ($color === $allColors[$i]) echo 'selected'; ?>>
                                <?php echo $color; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td style="width:80%; background-color: <?php echo $colorHex[$allColors[$i]]; ?>;">
                    <?php echo $allColors[$i]; ?>
                </td>
            </tr>
            <?php endfor; ?>
        </table>
        <table class="grid-table">
            <?php for ($r = 0; $r <= $rows; $r++): ?>
            <tr>
                <?php for ($c = 0; $c <= $rows; $c++): ?>
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
        <form method="POST" action="print.php" id="printForm">
            <input type="hidden" name="size" value="<?php echo $rows; ?>"> 
            <input type="hidden" name="numColors" value="<?php echo $numColors; ?>"> 
            <input type="hidden" name="selectedColors" id="chosenColorsInput" value="<?php echo implode(',', $selectedColors); ?>"> 
            <button type="submit"> Print </button>
        </form> 
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorHex = {
                'Red':'#FF0000','Orange':'#FFA500','Yellow':'#FFFF00','Green':'#008000',
                'Blue':'#0000FF','Purple':'#800080','Grey':'#808080','Brown':'#8B4513',
                'Black':'#000000','Teal':'#008080'
            };

            const dropdowns = document.querySelectorAll('.color-dropdown');
            const msgBox = document.getElementById('color-msg');

            dropdowns.forEach(dd => {
                dd.dataset.prev = dd.value;
            });

            
            dropdowns.forEach(dd => {
                dd.addEventListener('change', function() {
                    const chosen = this.value;
                    let duplicate = false;

                    dropdowns.forEach(other => {
                        if (other !== dd && other.value === chosen) {
                            duplicate = true;
                        }
                    });

                    if (duplicate) {
                        this.value = this.dataset.prev;
                        if (msgBox) {
                            msgBox.textContent = '"' + chosen + '" is already in use. Please pick a different color.';
                        }
                    } else {
                        this.dataset.prev = chosen;
                        if (msgBox) msgBox.textContent = '';

                        // Update the preview cell background color and text
                        const previewCell = this.closest('tr').querySelector('td:last-child');
                        previewCell.style.backgroundColor = colorHex[chosen];
                        previewCell.textContent = chosen;
                    }
                });
            });

            const printForm = document.getElementById('printForm');
            if (printForm) {
                printForm.addEventListener('submit', function() {
                    const colors = [];
                    dropdowns.forEach(dd => colors.push(dd.value));
                    document.getElementById('chosenColorsInput').value = colors.join(',');
                });
            }
        });
    </script>
    
    <footer>
        <p>&copy; 2026 404: Team Not Found — CSU CT312 Project</p>
    </footer>
</body>
</html>