<?php
require_once '../handlers/config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    }

    elseif (!isset($users[$email])) {
        $error = "Email not found❗";
    }
    
    elseif ($users[$email]['password'] !== $password) {
        $error = "Incorrect password❗";
    }
    else {
        $_SESSION['user'] = $users[$email];
        $_SESSION['loggedin'] = true;
        header("Location: home.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <?php require_once "../shared/styleFonts.php" ?>
    
</head>

<body>
    <?php require_once "../shared/header.php" ?>
     <?php if ($error): ?>
            <div class="error"> <?php echo $error; ?></div>
    <?php endif; ?>
    <main>
        
        <section id="login">
        <h2>Welcome Back!</h2>
        <form id="loginForm" method="POST" action="login.php">
            <fieldset>
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="enter you email address" required value="<?php echo $_POST['email'] ?? ''; ?>" >
                <br>
                <br>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" required>
            </fieldset>
            <br>
            <button id="loginBtn" type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p> <!-- صفحة signup-->
        </section>

    </main>
    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
    <script src="../assets/js/validation_login.js"></script>

</body>

</html>

