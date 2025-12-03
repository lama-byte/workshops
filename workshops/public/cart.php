<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">

</head>
<body>
    <header>
        <h1 class="logo">Star's Shop</h1>
        <nav class="headerNav">
            <a href="catalog.html">Products</a>
            <a href="#contactInfo">Contact</a>
            <a href="cart.html"><img src="cart.png" alt="cart" width="30"></a>
            <a href="home.html"><img src="exit.png" alt="logout" width="30"></a>
        </nav>
    </header>

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
        <p><b>Got Any Ideas? We’d love to hear from you!</b></p>
        <a id="Email" href="mailto: support@starsCrochet.com">📧 Email: support@starsCrochet.com</a>
        <a id="Phone number" href="tel: +966-12-345-6789">📞 Phone: +966 12 345 6789</a>
        <a id="Address">📍 Location: Madina, Saudi Arabia</a>
        <br>
        <br>
        <br>
        <p>© 2025 Stars Shop</p>
        
    </footer>
</body>
</html>