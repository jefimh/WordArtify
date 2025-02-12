<?php
session_start();
if (isset($_SESSION['sessionId'])) {
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="../assets/css/registration.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
    <script src="../assets/js/registration.js" type="text/javascript"></script>
</head>

<body>
    <header>
        <nav class="nav-container">
            <a href="landing.php" class="logo"><img src="../assets/images/logo-nobg.png" alt="Image of logo"></a>
            <ul class="nav-menu">
                <li><a href="home.php">Home</a></li>
                <li><a href="image-generator.php">Image Generator</a></li>
                <li><a href="create-post.php">Create Post</a></li>
                <li><a href="registration.php" class="selected">Registration</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="form-section">
            <form method="POST" action="registration-view.php">
                <h1>Registration</h1>
                <label for="username" id="usernameLabel">Create username</label>
                <?php
                if (isset($_SESSION['registrationErrors']['username'])) {
                    echo '<span class="errorMessage">' . $_SESSION['registrationErrors']['username'] . '</span>';
                }
                ?>
                <div class="input-section">
                    <i class="fas fa-user icon"></i>
                    <?php
                    if (isset($_SESSION['registrationUsername'])) {
                        $username = $_SESSION['registrationUsername'];

                        if (isset($_SESSION['registrationErrors']['username']) && $_SESSION['registrationErrors']['username']) {
                            echo "<input type=\"text\" id=\"username\" placeholder=\"Enter username\" class=\"error\" name=\"username\" value=\"$username\">";
                        } else {
                            echo "<input type=\"text\" id=\"username\" placeholder=\"Enter username\" class=\"input\" name=\"username\" value=\"$username\">";
                        }
                    } else {
                        if (isset($_SESSION['registrationErrors']['username']) && $_SESSION['registrationErrors']['username']) {
                            echo "<input type=\"text\" id=\"username\" placeholder=\"Enter username\" class=\"error\" name=\"username\">";
                        } else {
                            echo "<input type=\"text\" id=\"username\" placeholder=\"Enter username\" class=\"input\" name=\"username\">";
                        }
                    }
                    ?>
                </div>
                <label for="email">Enter email</label>
                <?php
                if (isset($_SESSION['registrationErrors']['email'])) {
                    echo '<span class="errorMessage">' . $_SESSION['registrationErrors']['email'] . '</span>';
                }
                ?>
                <div class="input-section">
                    <i class="fas fa-solid fa-envelope icon"></i>
                    <?php
                    if (isset($_SESSION['registrationEmail'])) {
                        $email = $_SESSION['registrationEmail'];

                        if (isset($_SESSION['registrationErrors']['email']) && $_SESSION['registrationErrors']['email']) {
                            echo "<input type=\"text\" id=\"email\" class=\"error\" name=\"email\"
                            placeholder=\"Enter your email\" value=\"$email\">";
                        } else {
                            echo "<input type=\"text\" id=\"email\" name=\"email\"
                            placeholder=\"Enter your email\" class=\"input\" value=\"$email\">";
                        }
                    } else {
                        if (isset($_SESSION['registrationErrors']['email']) && $_SESSION['registrationErrors']['email']) {
                            echo "<input type=\"text\" id=\"email\" class=\"error\" name=\"email\"
                            placeholder=\"Enter your email\">";
                        } else {
                            echo "<input type=\"text\" id=\"email\" name=\"email\"
                            placeholder=\"Enter your email\" class=\"input\">";
                        }
                    }
                    ?>
                </div>
                <label for="password">Create password</label>
                <?php
                if (isset($_SESSION['registrationErrors']['password'])) {
                    foreach ($_SESSION['registrationErrors']['password'] as $passwordError) {
                        echo '<span class="errorMessage">' . $passwordError . '</span>';
                    }
                }
                ?>
                <div class="input-section">
                    <i class="fas fa-lock icon"></i>
                    <?php
                    if (isset($_SESSION['registrationPassword'])) {
                        $password = $_SESSION['registrationPassword'];

                        if (isset($_SESSION['registrationErrors']['password']) && $_SESSION['registrationErrors']['password']) {
                            echo "<input type=\"password\" id=\"password\" class=\"error\" name=\"password\"
                            placeholder=\"Enter password\" value=\"$password\">";
                        } else {
                            echo "<input type=\"password\" id=\"password\" name=\"password\"
                            placeholder=\"Enter password\" class=\"input\" value=\"$password\">";
                        }
                    } else {
                        if (isset($_SESSION['registrationErrors']['password']) && $_SESSION['registrationErrors']['password']) {
                            echo "<input type=\"password\" id=\"password\" class=\"error\" name=\"password\"
                            placeholder=\"Enter password\">";
                        } else {
                            echo "<input type=\"password\" id=\"password\" name=\"password\"
                            placeholder=\"Enter password\" class=\"input\">";
                        }
                    }
                    ?>
                    <i id="eye" class="eye fa-sharp fa-solid fa-eye" onclick="handlePasswordVisibility()"></i>
                </div>
                <?php
                if (isset($_SESSION['registrationErrors']['passwordConfirmation'])) {
                    echo '<span class="errorMessage">' . $_SESSION['registrationErrors']['passwordConfirmation'] . '</span>';
                }
                ?>
                <div class="input-section">
                    <i class="fa-solid fa-check icon"></i>
                    <?php
                    if (isset($_SESSION['passwordConfirmation'])) {
                        $confirmationPassword = $_SESSION['passwordConfirmation'];

                        if (isset($_SESSION['registrationErrors']['passwordConfirmation']) && $_SESSION['registrationErrors']['passwordConfirmation']) {
                            echo "<input type=\"password\" id=\"passwordConfirmation\" class=\"error\" name=\"passwordConfirmation\"
                            placeholder=\"Enter password\" value=\"$confirmationPassword\">";
                        } else {
                            echo "<input type=\"password\" id=\"passwordConfirmation\" name=\"passwordConfirmation\"
                            placeholder=\"Enter password\" class=\"input\" value=\"$confirmationPassword\">";
                        }
                    } else {
                        if (isset($_SESSION['registrationErrors']['passwordConfirmation']) && $_SESSION['registrationErrors']['passwordConfirmation']) {
                            echo "<input type=\"password\" id=\"passwordConfirmation\" class=\"error\" name=\"passwordConfirmation\"
                            placeholder=\"Confirm password\">";
                        } else {
                            echo "<input type=\"password\" id=\"passwordConfirmation\" name=\"passwordConfirmation\"
                            placeholder=\"Confirm password\" class=\"input\">";
                        }
                    }
                    ?>
                </div>
                <button id="formSubmit" name="submit" type="submit">Register</button>
                <a class="link" href="login.php">Already registered? Login here</a>
                <?php
                if (isset($_SESSION['registrationErrors']['general']) && !empty($_SESSION['registrationErrors']['general'])) {
                    $error = $_SESSION['registrationErrors']['general'];
                    echo "<span class=\"errorMessage\">$error</span>";
                } else if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                    $message = $_SESSION['message'];
                    echo "<span>$message</span>";
                    unset($_SESSION['message']);
                }
                ?>
            </form>
        </div>
        <div class="image-section">
            <img id="image" src="../assets/images/registration-page-hero-min.png" alt="Login page image">
        </div>
    </div>
</body>

</html>