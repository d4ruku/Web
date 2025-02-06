<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$table = $_GET['table'] ?? null;
$category = $_GET['category'] ?? null;
if (!$table || !$category) {
    header('Location: tables.php');
    exit();
}

$menu = [
    "Lunch Appetizers" => ["Pickled Vegetables" => 5, "Rye Bread" => 5, "Cheese Blintzes" => 5],
    "Lunch Entrees" => ["Borscht" => 15, "Chicken Kiev" => 15, "Fish Pie" => 15],
    "Lunch Drinks" => ["Kvass" => 2, "Mors" => 2, "Black Tea" => 2],
    "Dinner Appetizers" => ["Garlic Bread" => 5, "Fried Potatoes" => 5, "Roasted Beets" => 5],
    "Dinner Entrees" => ["Duck a la Russe" => 15, "Caviar Platter" => 15, "Rabbit Stew" => 15],
    "Dinner Drinks" => ["Red Wine" => 2, "White Wine" => 2, "Vodka Shots" => 2]
];

$items = $menu[$category] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $price = $_POST['price'];
    if (!isset($_SESSION['cart'][$table])) {
        $_SESSION['cart'][$table] = [];
    }
    if (!isset($_SESSION['cart'][$table][$item])) {
        $_SESSION['cart'][$table][$item] = ['price' => $price, 'quantity' => 1];
    } else {
        $_SESSION['cart'][$table][$item]['quantity'] += 1;
    }
    // Redirect to avoid form resubmission
    header('Location: menu-items.php?table=' . $table . '&category=' . urlencode($category));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items</title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <div class="container">
        <h1><?= $category ?> for Table <?= $table ?></h1>
        <div class="main-layout">
            <!-- Left Column: Menu Items -->
            <div class="menu-column">
                <ul class="menu-items-list">
                    <?php foreach ($items as $item => $price): ?>
                        <li>
                            <span><?= $item ?> - $<?= $price ?></span>
                            <form action="menu-items.php?table=<?= $table ?>&category=<?= urlencode($category) ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="item" value="<?= $item ?>">
                                <input type="hidden" name="price" value="<?= $price ?>">
                                <button type="submit">Add to Order</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Right Column: Selected Items -->
            <div class="order-column">
                <h2>Selected Items</h2>
                <ul id="selected-items-list">
                    <?php if (isset($_SESSION['cart'][$table])): ?>
                        <?php foreach ($_SESSION['cart'][$table] as $item => $details): ?>
                            <li>
                                <strong><?= $item ?></strong> -
                                <?= $details['quantity'] ?> x $<?= $details['price'] ?>
                                = $<?= $details['quantity'] * $details['price'] ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <h3>Total: $<?= array_reduce($_SESSION['cart'][$table] ?? [], function ($carry, $item) {
                    return $carry + ($item['price'] * $item['quantity']);
                }, 0) ?></h3>
                <a href="menu.php?table=<?= $table ?>">Back to Categories</a>
            </div>
        </div>
    </div>
</body>
</html>