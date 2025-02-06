<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Initialize table statuses if not already set
if (!isset($_SESSION['tables'])) {
    $_SESSION['tables'] = array_fill(1, 10, 'free');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Table</title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <div class="container">
        <h1>Select a Table</h1>
        <div class="table-layout">
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <div class="table <?= $_SESSION['tables'][$i] === 'busy' ? 'busy' : 'free' ?>">
                    <?php if ($_SESSION['tables'][$i] === 'free'): ?>
                        <a href="menu.php?table=<?= $i ?>">Table <?= $i ?></a>
                    <?php else: ?>
                        <span>Table <?= $i ?> (Busy)</span>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>