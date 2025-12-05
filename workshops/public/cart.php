
<?php
require_once 'config.php';

$pdo = Database::getConnection();

// ------------------------- CART ACTIONS -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Clear Cart
    if (isset($_POST['clear_cart'])) {
        cart_clear();
        $_SESSION['message'] = "Cart cleared successfully";
        header("Location: cart.php");
        exit;
    }

    // Increase quantity
    if (isset($_POST['increase'])) {
        $workshopId = (int)$_POST['workshop_id'];
        $currentQty = $_SESSION['cart'][$workshopId] ?? 0;

        cart_update($workshopId, $currentQty + 1);
        $_SESSION['message'] = "Quantity increased";
        header("Location: cart.php");
        exit;
    }

    // Decrease quantity
    if (isset($_POST['decrease'])) {
        $workshopId = (int)$_POST['workshop_id'];
        $currentQty = $_SESSION['cart'][$workshopId] ?? 0;

        if ($currentQty > 1) {
            cart_update($workshopId, $currentQty - 1);
            $_SESSION['message'] = "Quantity decreased";
        } else {
            unset($_SESSION['cart'][$workshopId]);
            $_SESSION['message'] = "Item removed";
        }

        header("Location: cart.php");
        exit;
    }
}

// ------------------------- Load WORKSHOPS from DB ------------------
$cart_items = [];

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $workshopId => $quantity) {

        $stmt = $pdo->prepare("
            SELECT 
                id,
                title,
                price,
                seats_available,
                image,
                description
            FROM workshops
            WHERE id = ?
        ");

        $stmt->execute([$workshopId]);
        $row = $stmt->fetch();

        if ($row) {
            $row['quantity'] = $quantity;           // quantity fetched from session
            $cart_items[] = $row;
        }
    }
}

$total_quantity = array_sum($_SESSION['cart']);
$subtotal = cart_subtotal();

// logout
if (isset($_GET['logout'])) {
    session_destroy();
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
        <p id="server_msg"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <section id="cartStatus">

    <?php if (empty($cart_items)): ?>

        <p>Your cart is empty :(</p>

    <?php else: ?>

        <table>
            <tr>
                <th>Workshop</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach ($cart_items as $item): ?>
            <tr>

            <td>
                    <img src="/img/<?php echo htmlspecialchars($item['image']); ?>" width="150"> ///
                    <span><?php echo htmlspecialchars($item['title']); ?></span>
                </td>

                <td><?php echo number_format($item['price'], 2); ?> SAR</td>

             <td>
                    <form method="POST">
                        <input type="hidden" name="workshop_id" value="<?php echo $item['id']; ?>">

                        <button type="submit" name="decrease">-</button>

                        <input type="number"
                               value="<?php echo $item['quantity']; ?>"
                               min="1"
                               max="<?php echo $item['seats_available']; ?>"
                               readonly> <!-- prevent user to modfy quantity in checkout-->

                        <button type="submit" name="increase">+</button>
                    </form>
                </td>

                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> SAR</td>

            </tr>

            <?php endforeach; ?>
        </table>

        <p>Total items: <?php echo $total_quantity; ?></p>

        <section id="lastBtns">
            <form method="POST">
                <button type="submit" name="clear_cart">Clear Cart</button>
            </form>
        

    <?php endif; ?>

    

    <?php if ($total_quantity > 0): ?>
        <a href="checkout.php"><button>Proceed to Checkout</button></a>
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




                 
