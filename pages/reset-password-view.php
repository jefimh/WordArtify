<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require('../includes/dbconnection.php');
require('../includes/objects.php');
require('../includes/methods.php');

if (isset($_POST["submit"])) {
    $registrationErrors = array();
    $email = $_POST['email'];
    $sanitizedEmail = sanitizeInput($email);

    if (filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
        $user = User::retrieveUserByEmail($sanitizedEmail, $dbconn);

        if ($user != null) {
            $randomCode = md5(uniqid(rand(), true)) . time();
            $link = "http://php-projekt.test/pages/create-password.php?code=" . $randomCode;
            mail($sanitizedEmail, "WordArtify - Reset password", "<a href=\"$link\">Click me to reset password</a>");

            $passwordResetCode = new PasswordResetCode($user->getUserId($dbconn), $randomCode, "");
            $passwordResetCode->saveToTable($dbconn);
            $dbconn = null;
            $_SESSION['resetPasswordMessage'] = "<span>Password reset link has been sent to <a class=\"link\">$sanitizedEmail</a></span>
            Please check your inbox!";
            header("location: reset-password.php");
            exit();
        } else {
            $_SESSION['resetPasswordMessage'] = "<span class=\"errorMessage\">No registered account with such email found!</span>";
            header("location: reset-password.php");
            exit();
        }
    } else {
        $_SESSION['resetPasswordMessage'] = "<span class=\"errorMessage\">Invalid email address format!</span>";
        header("location: reset-password.php");
        exit();
    }
}
