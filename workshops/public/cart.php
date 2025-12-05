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
        <section id="cartStatus">
            <p>Your cart is empty :(</p>
            <!-- <table>
                <tr>
                    <th>product</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>subtotal</th>
                </tr>
                <tr>
                    <td>
                        <img src="" alt="product pic">
                        <span>product info</span>
                    </td>
                    <td>$price</td>
                    <td>
                        <button>+</button>
                        <span>0</span>
                        <button>-</button>
                    </td>
                    <td>$subtotal</td>
                </tr>
                here products will be added using php when the user selects them
            </table>  -->
            <p>total number of items: 0</p> 
        </section>
        <section id="checkout.html">
            <a href="checkout.html">
                <button disabled >Proceed to Checkout</button>
            </a>
            <!--should add a condition to not go to checkout unless there is atleast 1 item in cart -->
        </section>
        <hr>
    </main>
    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
</body>
</html>