
<?php 
require_once "config.php";
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
            <h1>Our Current Workshops !</h1>
            
            <!-- <label for="searchBar">Can't find a product?</label> -->
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

        <div id="productList">
            <br>
            <section class="row">

                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>

                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>
                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>
            </section>

            <section class="row">
                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>
                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>
                <section class="card">
                    <img src="/items/green_cat_keychain.jpg" alt="productImage" width="150">
                    <h3>Workshop name</h3>
                    <span>price$</span>
                    <div class="buttons">
                        <button>View Details</button>
                    </div>
                </section>
            </section>

        </div>

    </main>

    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
</body>

</html>