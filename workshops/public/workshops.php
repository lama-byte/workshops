<?php
require_once 'config.php';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($quantity > 0 && isset($products[$product_id])) {
        if ($products[$product_id]->reduceStock($quantity)) {
            $cart->addItem($product_id, $quantity);
        }
    }
}
elseif(isset($_POST['remove_from_cart'])){
   $product_id = intval($_POST['product_id']);
        $cart->removeItem($product_id);
    } 

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['loggedin']);

    header("Location: home.php");
    exit;
}

$filtered_products = searchProducts($search, $category);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/workshops.css">

    <?php require_once "../shared/styleFonts.php" ?>
</head>

<body>
    <?php require_once "../shared/header.php" ?>

        <main>
        <section id="intro">
            <form method="GET">
            <h1>Available Products</h1>
            <!-- <input id="searchBar" type="text" name="serach"
             placeholder="search for a product" 
             value="<?php echo htmlspecialchars($search); ?>"> -->

             <select class="filter" name="category">
                    <option value="">All Categories</option>
                    <option value="crochet" <?php echo $category === 'crochet' ? 'selected' : ''; ?>>Crochet</option>
                    <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                </select>

            <button class="filter" type="submit"><img src="/resources/search-icon.png" alt="search icon" width="45"></button>

            <?php if (!empty($search) || !empty($category)): ?>
                    <a href="catalog.php" style="margin-left: 10px;">Clear Filters</a>
                <?php endif; ?>
            </form>
        </section>

        <?php if (!empty($search) || !empty($category)): ?>
            <div >
                <?php if (!empty($search)): ?>
                    <p>Search: <strong>"<?php echo htmlspecialchars($search); ?>"</strong></p>
                <?php endif; ?>
                <?php if (!empty($category)): ?>
                    <p>Category: <strong><?php echo ucfirst($category); ?></strong></p>
                <?php endif; ?>
                <p>Found: <strong><?php echo count($filtered_products); ?> products</strong></p>
            </div>
        <?php endif; ?>

        <div id="productList">
            <br>
            <section class="row">
                <?php for($i = 0; $i < min(10, count($filtered_products)); $i++): ?>
                <?php if(isset($filtered_products[$i])): ?>
                <?php $product = $filtered_products[$i]; ?>
                <section class="card">
                    <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>" width=150>
                    <h3><?php echo $product->getName(); ?></h3>
                    <span><?php echo formatPrice($product->getPrice()); ?></span>
                    <p><?php echo $product->getDescription(); ?></p>

                    <div class="buttons">
                    <form method="POST" >
                        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="add_to_cart">+</button>
                    </form>
                    <form method="POST" >
                     <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                    <button type="submit" name="remove_from_cart">-</button>
                    </form>
                    </div>
                </section>
                    <?php endif; ?>
                <?php endfor; ?>
            </section>
        </div>

        <section id="cartBtn">
            <a href="cart.php">
                <button>View Shopping Cart (<?php echo $cart->getTotalQuantity(); ?>) </button>
            </a>
        </section>
    </main>

    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
</body>

</html>