<?php
require_once "config.php";
requireLogin();

$pdo = getPDO();
$message = "";

//  fetch products from cart.
$cart_items = [];
foreach ($_SESSION['cart'] as $productId => $quantity) {
    $stmt = $pdo->prepare("SELECT id, name, price, stock, image, description 
                           FROM products 
                           WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        $product['quantity'] = $quantity;
        $cart_items[] = $product;
    }
}

$total_quantity = array_sum($_SESSION['cart']);

$subtotal = cart_subtotal();
$tax = $subtotal * 0.15;
$total = $subtotal + $tax;

$totals = [
    'subtotal' => $subtotal,
    'tax' => $tax,
    'total' => $total
];

//  Complete Order..
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($cart_items)) {
        $message = "Your cart is empty.";
    } else {

        try {
            $pdo->beginTransaction();

       //  create order
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, subtotal, tax, total)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $subtotal,
                $tax,
                $total
            ]);

         $order_id = $pdo->lastInsertId();

            // check stock + create order_items + reduse  stock
            foreach ($cart_items as $p) {

                if ($p['stock'] < $p['quantity']) {
                    $pdo->rollBack();
                    $message = "Sorry, the following items are out of stock: " . $p['name'];
                    goto skip_commit;
                }

                //  order_item
                $stmt = $pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $order_id,
                    $p['id'],
                    $p['quantity'],
                    $p['price']
                ]);

                // reduse stock
                $stmt = $pdo->prepare("
                    UPDATE products 
                    SET stock = stock - ? 
                    WHERE id = ?
                ");
                $stmt->execute([
                    $p['quantity'],
                    $p['id']
                ]);
            }

            
            $pdo->commit();
            cart_clear();
            $message = "Order completed successfully!";

        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Something went wrong while processing your order.";
        }
    }
}

skip_commit:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>checkout page</title>
    <link rel="stylesheet" href="base.css">    
     <?php require_once "../shared/styleFonts.php" ?>

</head>

  <body>
  <?php require_once "../shared/header.php" ?>
    <main>
        <h1>Checkout</h1>
        <br>
        <h2>Your Order is Ready to Checkout!</h2>

        <?php if ($message !== ""): ?>
        <div class="msg <?php echo (strpos($message, 'successfully') === false) ? 'error' : ''; ?>">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <section id="cartTable">
            <table>
                <tr>
                    <th>product pic</th>
                    <th>product name</th>
                    <th>quantity</th>
                    <th>subtotal</th>
                </tr>

                <?php foreach ($cart_items as $product): ?>
                <tr>
                    <td>
                        <img src="/img/<?php echo e($product['image']); ?>">   <!-- مسار الصورة-->
                             alt="<?php echo e($product['name']); ?>" 
                             width="150">
                    </td>

                    <td><?php echo e($product['name']); ?></td>

                    <td><?php echo $product['quantity']; ?></td>

                    <td><?php echo number_format($product['price'] * $product['quantity'], 2); ?> SAR</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <hr>

        <section id="summary">
            <h2>Order Summary</h2>
            <br>

            <span>Number of Items: 
                <span class="summaryValue"><?php echo $total_quantity; ?></span>
            </span>

            <span>Sub Total: 
                <span class="summaryValue"><?php echo number_format($totals['subtotal'], 2); ?> SAR</span>
            </span>

            <span>Shipping: 
                <span class="summaryValue"><b>free!</b></span>
            </span>

            <span>tax (15%): 
                <span class="summaryValue"><?php echo number_format($totals['tax'], 2); ?> SAR</span>
            </span>

            <span><strong>Total:</strong> 
                <span class="summaryValue"><?php echo number_format($totals['total'], 2); ?> SAR</span>
            </span>
        </section>

        <hr>

        <section id="complete">
            <form method="POST"  >
                <button>Complete Order</button>
            </form>
        </section>
</main>
 <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
</body>
</html>
