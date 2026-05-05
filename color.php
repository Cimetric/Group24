<?php
$rows      = isset($_POST['rows'])      ? (int)$_POST['rows']      : null;
$numColors = isset($_POST['numColors']) ? (int)$_POST['numColors'] : null;

$errors = [];

if ($rows !== null && ($rows < 1 || $rows > 26)) {
    $errors[] = "Rows and Columns must be between 1 and 26.";
}

require __DIR__ . '/db.php';
$result    = $conn->query('SELECT name, hex_value FROM colors ORDER BY name');
$allColors = [];
$colorHex  = [];
while ($row = $result->fetch_assoc()) {
    $allColors[]            = $row['name'];
    $colorHex[$row['name']] = $row['hex_value'];
}


$maxColors = count($allColors);
if ($numColors !== null && ($numColors < 1 || $numColors > $maxColors)) {
    $errors[] = "Number of Colors must be between 1 and $maxColors (colors available in the database).";
}

$valid = empty($errors) && $rows !== null && $numColors !== null;
$selectedColors = [];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Color | 404: Team Not Found</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors_dynamic.php">
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
                <li><a href="colors.php">Color Selector</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <form method="POST" action="color.php">
            <label for="rows"> Rows and Columns (1-26): </label>
            <input type="number" name="rows" id="rows" min="1" max="26" value="<?php echo isset($rows) ? $rows : ''; ?>">
            <label for="numColors"> Number of Colors (1-<?php echo $maxColors; ?>): </label>
            <input type="number" name="numColors" id="numColors" min="1" max="<?php echo $maxColors; ?>" value="<?php echo isset($numColors) ? $numColors : ''; ?>">
            <button type="submit"> Generate </button>
        </form>

        <?php foreach ($errors as $err): ?>
        <p class="error"><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>

        <p id="color-msg" class="color-message"></p>

        <?php if ($valid): ?>
        <table class="color-table">
            <?php for ($i = 0; $i < $numColors; $i++): ?>
            <tr>
                <td style="width:5%; text-align:center;">
                    <input type="radio" name="activeColor" class="color-radio" value="<?php echo $i; ?>" <?php if ($i === 0) echo 'checked'; ?>>
                </td>
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
                <td style="width:40%; background-color: <?php echo $colorHex[$allColors[$i]]; ?>;" id="preview-<?php echo $i; ?>">
                    <?php echo $allColors[$i]; ?>
                </td>
                <td style="width:35%;" id="coords-<?php echo $i; ?>"></td>
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
                    <td class="paintable" data-row="<?php echo $r; ?>" data-col="<?php echo $c; ?>"></td>
                <?php endif; ?>
                <?php endfor; ?>
            </tr>
            <?php endfor; ?>
        </table>
        <form method="POST" action="print.php" id="printForm">
            <input type="hidden" name="size" value="<?php echo $rows; ?>">
            <input type="hidden" name="numColors" value="<?php echo $numColors; ?>">
            <input type="hidden" name="selectedColors" id="chosenColorsInput" value="">
            <input type="hidden" name="hexValues" id="hexValuesInput" value="">
            <input type="hidden" name="coordinates" id="coordinatesInput" value="">
            <input type="hidden" name="maxColors" id="maxColors" value="<?php echo $maxColors; ?>">
            <button type="submit"> Print </button>
        </form>
        <?php endif; ?>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorHex = <?php echo json_encode($colorHex); ?>;

            const dropdowns = document.querySelectorAll('.color-dropdown');
            const radios = document.querySelectorAll('.color-radio');
            const paintable = document.querySelectorAll('.paintable');
            const msgBox = document.getElementById('color-msg');

            const colorCoords = Array.from({length: <?php echo $valid ? $numColors : 0; ?>}, () => new Set());

            dropdowns.forEach(dd => { dd.dataset.prev = dd.value; });

            function getActiveIndex() {
                for (const r of radios) {
                    if (r.checked) return parseInt(r.value);
                }
                return 0;
            }

            function sortCoords(arr) {
                return [...arr].sort((a, b) => {
                    if (a[0] !== b[0]) return a.charCodeAt(0) - b.charCodeAt(0);
                    return parseInt(a.slice(1)) - parseInt(b.slice(1));
                });
            }

            function updateCoordsDisplay(idx) {
                const el = document.getElementById('coords-' + idx);
                if (el) el.textContent = sortCoords([...colorCoords[idx]]).join(', ');
            }

            paintable.forEach(cell => {
                cell.addEventListener('click', function() {
                    const activeIdx = getActiveIndex();
                    const hex = colorHex[dropdowns[activeIdx].value];
                    const coord = String.fromCharCode(64 + parseInt(this.dataset.col)) + this.dataset.row;
                    const prevOwner = this.dataset.colorIndex;

                    if (prevOwner !== undefined && parseInt(prevOwner) !== activeIdx) {
                        colorCoords[parseInt(prevOwner)].delete(coord);
                        updateCoordsDisplay(parseInt(prevOwner));
                    }

                    this.style.backgroundColor = hex;
                    this.dataset.colorIndex = activeIdx;
                    colorCoords[activeIdx].add(coord);
                    updateCoordsDisplay(activeIdx);
                });
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
                        const idx = parseInt(this.dataset.index);
                        const newHex = colorHex[chosen];
                        this.dataset.prev = chosen;
                        if (msgBox) msgBox.textContent = '';

                        const previewCell = document.getElementById('preview-' + idx);
                        previewCell.style.backgroundColor = newHex;
                        previewCell.textContent = chosen;

                        paintable.forEach(cell => {
                            if (parseInt(cell.dataset.colorIndex) === idx) {
                                cell.style.backgroundColor = newHex;
                            }
                        });
                    }
                });
            });

            const printForm = document.getElementById('printForm');
            if (printForm) {
                printForm.addEventListener('submit', function() {
                    const colors = [], hexes = [];
                    dropdowns.forEach(dd => {
                        colors.push(dd.value);
                        hexes.push(colorHex[dd.value]);
                    });
                    const coordsData = colorCoords.map(set => sortCoords([...set]).join(', '));

                    document.getElementById('chosenColorsInput').value = colors.join(',');
                    document.getElementById('hexValuesInput').value = hexes.join(',');
                    document.getElementById('coordinatesInput').value = JSON.stringify(coordsData);
                });
            }
        });
    </script>

    <footer>
        <p>&copy; 2026 404: Team Not Found — CSU CT312 Project</p>
    </footer>
</body>
</html>