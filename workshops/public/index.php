<?php
require_once 'config.php';
if (isLoggedIn()) {
    header("Location: catalog.php");
    exit();
}

$message = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <?php require_once "../shared/styleFonts.php" ?>
</head>

<body>
    <?php require_once "../shared/header.php" ?>
    <main>
        <section id="welcome">
            <p>Welcome to .. </p>
            <h1>Learning Aura Workshops</h1>
            <p>Where ideas glow brighter ⭐</p>
            <br>
            <p><b>Learning Aura is an online platform that offers  
                beginner-friendly cybersecurity workshops. The website provides 
                easy-to-follow sessions in important areas such as cryptography, 
                computer networks, security basics, web security, and safe online 
                practices. Each workshop is designed to help learners build practical 
                skills and gain a clear understanding of key cybersecurity concepts. 
                Learning Aura makes it simple for students and beginners to choose a 
                workshop, purchase it, and start learning at their own pace.</b></p>

            <?php if ($message !== ""): ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <main id="reasons">
        <h1>Why you should choose Us ? </h1>
            <section class="card">
                    <h3>reason1</h3>
            </section>
            <section class="card">
                    <h3>reason2</h3>
            </section>
            <section class="card">
                    <h3>reason3</h3>
            </section>
    </main>
    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
</body>

</html>