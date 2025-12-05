<?php
require_once '../handlers/config.php';
require_once __DIR__.'/../../protected/config/database.php';


$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = getConnection();

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // -----------------------
    //   VALIDATION
    // -----------------------
    if (empty($first_name) || empty($last_name) || empty($email) ||
        empty($password) || empty($confirm_password)) {

        $message = "All fields are required";

    } elseif (!validateEmail($email)) {

        $message = "Please enter a valid email address";

    } elseif (strlen($password) < 8) {

        $message = "Password must be at least 8 characters";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match";

    } else {

        // -----------------------
        //   CHECK EMAIL IN DB
        // -----------------------
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $message = "Email already registered";

        } else {

            // -----------------------
            //   INSERT NEW USER
            // -----------------------
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare("
                INSERT INTO users (first_name, last_name, email, password)
                VALUES (?, ?, ?, ?)
            ");

            $insert->execute([$first_name, $last_name, $email, $hashed]);

            // Save info in session
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $first_name;
            $_SESSION['loggedin'] = true;

            $message = "Registration successful!";
            $success = true;

            header("Refresh: 2; URL=catalog.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeration page - Star's Shop</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/signup.css">
    <?php require_once "../shared/styleFonts.php" ?>
    
</head>

<body>
    <?php require_once "../shared/header.php" ?>

    <main>
            <section id="signup">
            <h2>Create a New Account</h2>
            <?php if ($message !== ""): ?>
                <div class="msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
            </div>
            <?php endif; ?>
            <form id="signUpForm" method="post"  action= "<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset class="row">
                    <div>
                        <label> First name </label>
                        <input  name="first_name" id="fname" type="text" placeholder="Enter you first name" required value="<?php echo $_POST['first_name'] ?? ''; ?>">
                    </div>
                    <div>
                        <label> Last name</label>
                        <input name="last_name" type="text" id="lname"  placeholder="Enter your last name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                    </div>
                </fieldset>
                <fieldset id="Email">
                    <label> Email address</label> <br>
                    <input type="email" id="email" name="email" placeholder="examble@ex.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </fieldset>
                <fieldset class="row">
                    <div class="field">
                        <label> Password</label>
                        <input name="password" type="password" id="password1" placeholder="Enter your password" required>
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input name="confirm_password" type="password" id="password2" placeholder="Enter your password again" required>
                    </div>
                </fieldset>
                <div id="underPass">
                    <button id="togglePassword" type="button">show password</button>
                        <p id="liveFeedback"></p>
                </div>
                <button id="signUpBtn" type="submit">Sign up</button>
            </form>
            <p>Already have an account?
                <a href="login.php">login</a>
            </p>
        </section>
    </main>

    <footer id="contactInfo">
    <?php require_once "../shared/footer.php"?>
    </footer>
<script src="../assets/js/validation_signup.js"></script>
</body>

</html>
