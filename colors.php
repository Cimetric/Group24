<?php
require __DIR__ . '/db.php';

$errors = [];

//Add 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $hex  = strtoupper(trim($_POST['hex_value'] ?? ''));

    if ($name === '') {
        $errors[] = 'Color name is required.';
    }
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
        $errors[] = 'Hex value must be in the format #RRGGBB (e.g. #FF0000).';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM colors WHERE name = ? OR hex_value = ?');
        $stmt->bind_param('ss', $name, $hex);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'A color with that name or hex value already exists.';
        } else {
            $ins = $conn->prepare('INSERT INTO colors (name, hex_value) VALUES (?, ?)');
            $ins->bind_param('ss', $name, $hex);
            $ins->execute();
            header('Location: colors.php?msg=added');
            exit;
        }
    }
}
//Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_confirm') {
    $id = (int)($_POST['delete_id'] ?? 0);

    $cnt = $conn->prepare('SELECT COUNT(*) FROM colors');
    $cnt->execute();
    $cnt->bind_result($total);
    $cnt->fetch();
    $cnt->close();

    if ($total <= 2) {
        $errors[] = 'Cannot delete: at least 2 colors must remain.';
    } else {
        $del = $conn->prepare('DELETE FROM colors WHERE id = ?');
        $del->bind_param('i', $id);
        $del->execute();
        header('Location: colors.php?msg=deleted');
        exit;
    }
}
//Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id   = (int)($_POST['edit_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $hex  = strtoupper(trim($_POST['hex_value'] ?? ''));

    if ($name === '') {
        $errors[] = 'Color name is required.';
    }
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
        $errors[] = 'Hex value must be in the format #RRGGBB (e.g. #FF0000).';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM colors WHERE (name = ? OR hex_value = ?) AND id <> ?');
        $stmt->bind_param('ssi', $name, $hex, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Another color already uses that name or hex value.';
        } else {
            $upd = $conn->prepare('UPDATE colors SET name = ?, hex_value = ? WHERE id = ?');
            $upd->bind_param('ssi', $name, $hex, $id);
            $upd->execute();
            header('Location: colors.php?msg=updated');
            exit;
        }
    }
}
//Reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_colors') {
    $conn->query('DELETE FROM colors');
    $conn->query("INSERT INTO colors (name, hex_value) VALUES
        ('Red','#FF0000'),('Orange','#FFA500'),('Yellow','#FFFF00'),
        ('Green','#008000'),('Blue','#0000FF'),('Purple','#800080'),
        ('Grey','#808080'),('Brown','#A52A2A'),('Black','#000000'),('Teal','#008080')");
    $redirect = $_POST['redirect'] ?? 'colors';
    header('Location: ' . ($redirect === 'color' ? 'color.php' : 'colors.php') . '?msg=reset');
    exit;
}
//Load
$result = $conn->query('SELECT id, name, hex_value FROM colors ORDER BY id');
$colors = $result->fetch_all(MYSQLI_ASSOC);

$editId  = isset($_POST['edit_id'])   ? (int)$_POST['edit_id']   : null;
$delId   = isset($_POST['delete_id']) ? (int)$_POST['delete_id'] : null;
$delStep = isset($_POST['action']) && $_POST['action'] === 'delete_step';

$successMsg = match($_GET['msg'] ?? '') {
    'added'   => 'Color added successfully.',
    'updated' => 'Color updated successfully.',
    'deleted' => 'Color deleted successfully.',
    'reset'   => 'Color list reset to defaults.',
    default   => ''
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Selector | 404: Team Not Found</title>
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
                <li><a href="color.php">Color Coordinator</a></li>
                <li class="active"><a href="colors.php">Color Selector</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        
        <?php if ($successMsg): ?>
            <div class="notice"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>

        <?php foreach ($errors as $e): ?>
            <div class="error"><?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>
        <section class = "services">
            <h2>Add a Color</h2>
        </section>
        <form method="POST" action="colors.php">
            <input type="hidden" name="action" value="add">
            <label for="add-name">Name:</label>
            <input type="text" id="add-name" name="name" maxlength="100"
                value="<?php echo isset($_POST['action']) && $_POST['action'] === 'add' ? htmlspecialchars($_POST['name'] ?? '') : ''; ?>">
            <label for="add-hex">Hex Value:</label>
            <input type="color" id="add-hex" name="hex_value"
                value="<?php echo isset($_POST['action']) && $_POST['action'] === 'add' && preg_match('/^#[0-9A-Fa-f]{6}$/', $_POST['hex_value'] ?? '') ? strtoupper(htmlspecialchars($_POST['hex_value'])) : '#000000'; ?>">
            <button type="submit">Add Color</button>
        </form>


        <section class = "services">
            <h2>Delete a Color</h2>
        </section>
        <?php if (!$delStep): ?>
        <form method="POST" action="colors.php">
            <input type="hidden" name="action" value="delete_step">
            <label for="del-select">Select color to delete:</label>
            <select id="del-select" name="delete_id">
                <?php foreach ($colors as $c): ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo htmlspecialchars($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Delete</button>
        </form>
        <?php else:
            $delName = '';
            foreach ($colors as $c) {
                if ((int)$c['id'] === $delId) { $delName = $c['name']; break; }
            }
        ?>
        <div class="notice">
            Are you sure you want to delete <strong><?php echo htmlspecialchars($delName); ?></strong>?
        </div>
        <form method="POST" action="colors.php" class="form-inline">
            <input type="hidden" name="action" value="delete_confirm">
            <input type="hidden" name="delete_id" value="<?php echo $delId; ?>">
            <button type="submit">Confirm Delete</button>
            <button type="submit">Cancel</button>
        </form>
        <?php endif; ?>


         <section class = "services">
            <h2>Edit a Color</h2>
        </section>
        
                <form method="POST" action="colors.php">
            <input type="hidden" name="action" value="edit_select">
            <label for="edit-select">Select color to edit:</label>
            <select id="edit-select" name="edit_id">
                <?php foreach ($colors as $c): ?>
                    <option value="<?php echo $c['id']; ?>"
                        <?php if ($editId === (int)$c['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Load</button>
        </form>

        <?php
        $editColor = null;
        if ($editId) {
            foreach ($colors as $c) {
                if ((int)$c['id'] === $editId) { $editColor = $c; break; }
            }
        }
        if (isset($_POST['action']) && $_POST['action'] === 'edit_select' && $editId) {
            foreach ($colors as $c) {
                if ((int)$c['id'] === $editId) { $editColor = $c; break; }
            }
        }
        ?>
        <?php if ($editColor): ?>
        <form method="POST" action="colors.php" class="form-mt">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="edit_id" value="<?php echo $editColor['id']; ?>">
            <label for="edit-name">Name:</label>
            <input type="text" id="edit-name" name="name" maxlength="100"
                value="<?php echo isset($_POST['action']) && $_POST['action'] === 'edit' ? htmlspecialchars($_POST['name'] ?? '') : htmlspecialchars($editColor['name']); ?>">
            <label for="edit-hex">Hex Value:</label>
            <input type="color" id="edit-hex" name="hex_value"
                value="<?php echo isset($_POST['action']) && $_POST['action'] === 'edit' && preg_match('/^#[0-9A-Fa-f]{6}$/', $_POST['hex_value'] ?? '') ? htmlspecialchars($_POST['hex_value']) : htmlspecialchars($editColor['hex_value']); ?>">
            <button type="submit">Save Changes</button>
        </form>
        <?php endif; ?>

        <section class = "services">
            <h2>Current Colors</h2>
        </section>
        <table class = "color-table">
                <tbody>
                <?php foreach ($colors as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['name']); ?></td>
                    <td><?php echo htmlspecialchars($c['hex_value']); ?></td>
                    <td><div class="swatch" style="background-color:<?php echo htmlspecialchars($c['hex_value']); ?>;"></div></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST" action="colors.php" style="display:inline;">
            <input type="hidden" name="action" value="reset_colors">
            <button type="submit">Reset Color List</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2026 404: Team Not Found — CSU CT312 Project</p>
    </footer>
</body>
</html>
