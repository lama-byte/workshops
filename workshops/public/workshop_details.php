<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Details</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/details.css">

    <?php require_once "../shared/styleFonts.php" ?>
</head>
<body>
    <?php require_once "../shared/header.php" ?>
        <main class="workshop-page">

        <section class="workshop-image">
            <img src="" alt="Workshop Image">
        </section>

        <section class="workshop-info">
            <h2 class="workshop-title">Workshop Name</h2>
            <h3 class="workshop-price">$00.00</h3>

            <p class="workshop-description">
                Workshop description 
            </p>

            <details class="workshop-details">
                <summary>Read more</summary>
                <p>
                    an extended details here:
                </p>
            </details>
            <br>
        </section>
        
        
        <br>
        <section class="buttons">
                <div class="quantity-box">
                    <button class="qty-btn">+</button>
                    <span class="qty-number">1</span>
                    <button class="qty-btn">-</button>
                </div>
                
                <button id="addBtn" type="submit">Add To Cart</button>
                <button id="RegBtn"type="submit">Register Now</button>
            </section>
        <article class="workshop-reviews">
            <section>
                <h3>Share Your Opinion</h3>

                <form action="" method="POST" class="review-form">
                    <input type="text" name="review" placeholder="Write your review here..." required>
                    <button type="submit">Send</button>
                </form>
            </section>
        </article>
        <br>
    
        <!-- <article>
            <h2>Other Workshops you might be interested in !</h2>
            <button class="scroll-btn left" id="btnLeft">❮</button>
            <button class="scroll-btn right" id="btnRight">❯</button>

            <div class="carousel-container" id="carousel">
                <div class="card">
                    <img src="img/db.jpg" alt="Database">
                    <h3>Suggested Course name</h3>
                </div>
            </div>
        </article> -->
    </main>

    <footer>
        <?php require_once "../shared/footer.php"?>
    </footer>
</body>
</html>