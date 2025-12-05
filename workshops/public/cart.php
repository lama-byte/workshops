<?php
require_once 'config.php';  

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Clear Cart
    if (isset($_POST['clear_cart'])) {
        cart_clear();
        $_SESSION['message'] = "Cart cleared successfully";
        header("Location: cart.php");
        exit;
    }

    // Increase
    if (isset($_POST['increase'])) {
        $productId = (int)$_POST['product_id'];
        $currentQty = $_SESSION['cart'][$productId] ?? 0;
        cart_update($productId, $currentQty + 1);
        $_SESSION['message'] = "Quantity increased";
        header("Location: cart.php");
        exit;
    }
    // Decrease
    if (isset($_POST['decrease'])) {
        $productId = (int)$_POST['product_id'];
        $currentQty = $_SESSION['cart'][$productId] ?? 0;

        if ($currentQty > 1) {
            cart_update($productId, $currentQty - 1);
            $_SESSION['message'] = "Quantity decreased";
        } else {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['message'] = "Item removed";
        }

        header("Location: cart.php");
        exit;
    }
}

// ---------Loading products from the DB based on cart ----------
$cart_items = [];

foreach ($_SESSION['cart'] as $productId => $quantity) {
    $stmt = $pdo->prepare("SELECT id, name, price, stock, image, description 
                            FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        $product['quantity'] = $quantity;
        $cart_items[] = $product;
    }
}

// total quantity
$total_quantity = array_sum($_SESSION['cart']);

// ----------  Subtotal ----------
$subtotal = cart_subtotal();   // موجودة داخل config.php

// ---------- Logout ---------- ////
if (isset($_GET['logout'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    unset($_SESSION['loggedin']);
    header("Location: home.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="cart.css">
    <?php require_once "../shared/styleFonts.php" ?>

</head>
<body>
    <?php require_once "../shared/header.php" ?>
 <main>
        <h2>Shopping Cart</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <p id="server_msg"><?php echo e($_SESSION['message']); ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <section id="cartStatus">
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty :(</p>

        <?php else: ?>
<table>
            <tr>
                    <th>product</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>subtotal</th>
             </tr>
        <?php foreach ($cart_items as $product): ?>
            <tr>
                 <td>
                     <img src="/img/<?php echo e($product['image']); ?>">               <!--مسار الصورة-->
                      alt="<?php echo e($product['name']); ?>" width="150">
                     <span><?php echo e($product['name']); ?></span>
                        </td>
                 <td><?php echo number_format($product['price'], 2); ?> SAR</td>

                 <td> 
                    <form method="POST">
                            <input type="hidden" name="product_id"  value="<?php echo $product['id']; ?>">

                            <button type="submit" name="decrease" class="quantity-btn">-</button>
                           <input type="number"
                                    value="<?php echo $product['quantity']; ?>"
                                    min="1"
                                    max="<?php echo $product['stock']; ?>"
                                    class="quantity-input" readonly>     <!-- prevent user to modfy quantity in checkout-->

                                <button type="submit" name="increase" class="quantity-btn">+</button>
                            </form>
                        </td>
                    <td>
                        <?php echo number_format($product['price'] * $product['quantity'], 2); ?> SAR
                    </td>
                    </tr>
                <?php endforeach; ?>
            </table>
       <p>Total number of items: <?php echo $total_quantity; ?></p>

            <section id="lastBtns">

                <form method="POST">
                    <button type="submit" name="clear_cart">Clear Cart</button>
                </form>

            <?php endif; ?> 

            <?php if ($total_quantity > 0): ?>
                <a href="checkout.php">
                    <button>Proceed to Checkout</button>
                </a>
            <?php else: ?>
                <button disabled>Proceed to Checkout</button>
            <?php endif; ?>

            </section>
        </section>

        <hr>
    </main>
      <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
     </footer>
 </body>

 
</html>     
                 
