<?php
require 'db.php';

$pages = ['Pets' => 'pets.php', 'Owners' => 'owners.php', 'Owns' => 'owns.php', 'Foods' => 'foods.php', 'Purchases' => 'purchases.php'];
$shown = array_unique(array_merge($keys, $columns));
$error = '';

function h($value) {
    return htmlspecialchars($value ?? '');
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array_map(fn($c) => ($_POST['data'][$c] ?? '') === '' ? null : $_POST['data'][$c], $columns);
    $old = array_map(fn($k) => $_POST['old'][$k] ?? null, $keys);
    $where = implode(' AND ', array_map(fn($k) => "$k = ?", $keys));

    try {
        if($_POST['action'] === 'create') {
            $marks = implode(', ', array_fill(0, count($columns), '?'));
            $stmt = $pdo->prepare("INSERT INTO $table (" . implode(', ', $columns) . ") VALUES ($marks)");
            $stmt->execute($data);
        } else if($_POST['action'] === 'update') {
            $set = implode(', ', array_map(fn($c) => "$c = ?", $columns));
            $stmt = $pdo->prepare("UPDATE $table SET $set WHERE $where");
            $stmt->execute([...$data, ...$old]);
        } else if($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM $table WHERE $where");
            $stmt->execute($old);
        }

        header('Location: ' . $pages[$table]);
        exit;
    } catch(PDOException $e) {
        $error = $e->errorInfo[2] ?? $e->getMessage();
    }
}

$rows = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $table ?></title>

    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <?php foreach($pages as $name => $href): ?>
            <?php if($name === $table): ?>
                <a class="utility-button current"><?= $name ?></a>
            <?php else: ?>
                <a href="<?= $href ?>" class="utility-button"><?= $name ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="page-body">
        <h1><?= $table ?></h1>
        <p class="error"><?= h($error) ?></p>

        <table>
            <tr>
                <?php foreach($shown as $col): ?>
                    <th><?= $col ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>

            <?php foreach($rows as $i => $row): ?>
                <tr>
                    <?php foreach($shown as $col): ?>
                        <td>
                            <?php if(in_array($col, $columns)): ?>
                                <input form="row<?= $i ?>" name="data[<?= $col ?>]" value="<?= h($row[$col]) ?>" class="user-input">
                            <?php else: ?>
                                <?= h($row[$col]) ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <form id="row<?= $i ?>" method="post" class="actions">
                            <?php foreach($keys as $key): ?>
                                <input type="hidden" name="old[<?= $key ?>]" value="<?= h($row[$key]) ?>">
                            <?php endforeach; ?>
                            <button name="action" value="update" class="utility-button dark small">Update</button>
                            <button name="action" value="delete" class="utility-button dark small">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <?php foreach($shown as $col): ?>
                    <td>
                        <?php if(in_array($col, $columns)): ?>
                            <input form="new" name="data[<?= $col ?>]" placeholder="<?= $col ?>" class="user-input">
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <td>
                    <form id="new" method="post" class="actions">
                        <button name="action" value="create" class="utility-button dark small">Add</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</body>
