<?php
require_once "config.php";
requireLogin();

$pdo = getPDO();
$message = "";

//   fetch products from cart.
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
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <?php require_once "../shared/styleFonts.php" ?>
    <style>

=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>checkout page</title>
    <link rel="stylesheet" href="base.css">    
     <?php require_once "../shared/styleFonts.php" ?>
<style>
>>>>>>> 736182cb3d52250d7a60f0430144602a46670903
    main {
        background-color: whitesmoke;
        padding: 3em;
        margin: 2em auto;
        width: 80%;
        border-radius: 15px;
    }

    main h1 {
        font-family: "Unbounded", cursive;
        font-weight: 700;
        color: rgb(130, 73, 130);
        font-size: 2.6em;
        text-align: center;
        margin-bottom: 0.5em;
    }

    main h2 {
        font-family: "Unbounded", cursive;
        font-weight: 600;
        color: rgb(130, 73, 130);
        font-size: 1.7em;
        text-align: center;
        margin-bottom: 1.2em;
    }

    table {
        width: 90%;
        margin: 1.5em auto;
        border-collapse: collapse;
        font-family: "Unbounded", cursive;
    }

    table th {
        background-color: rgb(130, 73, 130);
        color: white;
        padding: 12px;
        font-weight: 600;
    }

    table td {
        padding: 15px;
        border-bottom: 2px solid rgb(205, 205, 205);
        text-align: center;
    }

    table img {
        width: 70px;
        border-radius: 10px;
        border: 2px solid lightyellow;
    }

    main section span,
    main section p {
        display: block;
        font-family: "Unbounded", cursive;
        font-size: 1.1em;
        margin: 6px auto;
        text-align: center;
        color: rgb(70, 70, 70);
    }

    main section p:last-child {
        font-weight: 600;
        font-size: 1.3em;
        color: rgb(130, 73, 130);
        margin-top: 10px;
    }


    main section button {
        font-size: 22px;
        font-family: "Lemon", serif;
        cursor: pointer;
        padding: 15px 40px;
        border-radius: 15px;
        border: 2.5px solid rgb(130, 73, 130);
        background-color: transparent;
        color: rgb(130, 73, 130);
        display: block;
        margin: 2em auto 0;
        transition: 0.2s;
    }

    main section button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }
    #summary {
        margin: 5em 10em;
    }

    #summary>span{
        display: flex;
        justify-content: space-around;
        width: 100%;
        font-size: 1.1em;
        font-family: "Unbounded", cursive;
        white-space: nowrap;
    }

    #summary>span>span{
        margin-right: 0;
        width: 100%;
        text-align: right;
        font-weight: 700;
        font-size: 1.1em;
    }

    hr {
        margin: 3em auto;
    }
    </style>
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
                        <img src="/img/<?php echo e($product['image']); ?>">    <!-- مسار الصورة-->
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
