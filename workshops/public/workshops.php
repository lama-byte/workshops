<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="catalogStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1 class="logo">Star's Shop</h1>
        <nav class="headerNav">
            <a href="#productList">Products</a>
            <a href="#contactInfo">Contact</a>
            <a href="cart.html"><img src="cart.png" alt="cart" width="30"></a>
            <a href="home.html"><img src="exit.png" alt="logout" width="30"></a>
        </nav>
    </header>

    <main>
        <section id="intro">
            <h1>Available Products</h1>
            
            <!-- <label for="searchBar">Can't find a product?</label> -->
            <input id="searchBar" type="text" placeholder="search for a product">
        </section>

        <div id="productList">
            <br>
            <section class="row">

                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Green Hooded Kitty Doll</h3>
                    <span>60 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/blue_headband.jpg" alt="productImage" width="150">
                    <h3>Blue Button Headband</h3>
                    <span>35 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/fingerless_gloves.jpg" alt="productImage" width="150">
                    <h3>Rose Spiral Fingerless Gloves</h3>
                    <span>55 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/blue_apple_hat.jpg" alt="productImage" width="150">
                    <h3>Blue Apple Beanie</h3>
                    <span>70 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/frog_beanie.jpg" alt="productImage" width="150">
                    <h3>Smily Frog Beanie</h3>
                    <span>66 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>
                <br>
            </section>

            <section class="row">
                <br>
                <section class="card">
                    <img src="/items/funky_beanie.jpg" alt="productImage" width="150">
                    <h3>Boho Earthy Tone Hat</h3>
                    <span>75 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/apple_keychain.jpg" alt="productImage" width="150">
                    <h3>Apple Charm</h3>
                    <span>20 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/red_apple_scarf.jpg" alt="productImage" width="150">
                    <h3>Red Cozy Scarf</h3>
                    <span>80 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/red_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Red Hooded Kitty Doll</h3>
                    <span>60 SAR</span>
                    <div class="buttons">
                        <button type="button">+</button>
                        <button>-</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/red_headband.jpg" alt="productImage" width="150">
                    <h3>Red Button Headband</h3>
                    <span>35 SAR</span>
                    <div class="buttons">
                        <button>+</button>
                        <button>-</button>
                    </div>
                </section>
            </section>

        </div>

        <section id="cartBtn">
            <a href="cart.html">
                <button>View Shopping Cart</button>
            </a>
        </section>
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