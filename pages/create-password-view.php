<?php
session_start();

if (isset($_POST['code'])) {
    $code = $_POST['code'];
}

$_SESSION['createPasswordMessage'] = array();

if (
    isset($_POST['password']) && !empty($_POST['password'])
    && isset($_POST['passwordConfirmation']) && !empty($_POST['passwordConfirmation'])
) {
    require('../includes/dbconnection.php');
    require('../includes/objects.php');
    require('../includes/methods.php');

    $_SESSION['createPassword'] = $_POST['password'];
    $_SESSION['createPasswordConfirmation'] = $_POST['passwordConfirmation'];

    $sanitizedPassword = sanitizeInput($_POST['password']);
    $sanitizedPasswordConfirmation = sanitizeInput($_POST['passwordConfirmation']);

    $_SESSION['createPasswordMessage']['password'] = array();
    if (strlen($sanitizedPassword) < 8) {
        $_SESSION['createPasswordMessage']['password'][] = "Password must be at least 8 characters long!";
    } else {
        if (($arrayElement = array_search(
            "Password must be at least 8 characters long!",
            $_SESSION['createPasswordMessage']['password']
        )) !== false) {
            unset($_SESSION['createPasswordMessage']['password'][$arrayElement]);
        }
    }

    if (!preg_match('/[a-zA-Z]/', $sanitizedPassword)) {
        $_SESSION['createPasswordMessage']['password'][] = "The password must contain at least one letter!";
    } else {
        if (($arrayElement = array_search(
            "The password must contain at least one letter!",
            $_SESSION['createPasswordMessage']['password']
        )) !== false) {
            unset($_SESSION['createPasswordMessage']['password'][$arrayElement]);
        }
    }

    if (!preg_match('/\d/', $sanitizedPassword)) {
        $_SESSION['createPasswordMessage']['password'][] = "The password must contain at least one digit!";
    } else {
        if (($arrayElement = array_search(
            "The password must contain at least one digit!",
            $_SESSION['createPasswordMessage']['password']
        )) !== false) {
            unset($_SESSION['createPasswordMessage']['password'][$arrayElement]);
        }
    }

    if (!preg_match('/[^a-zA-Z0-9\s]/', $sanitizedPassword)) {
        $_SESSION['createPasswordMessage']['password'][] = "The password must contain at least one special character!";
    } else {
        if (($arrayElement = array_search(
            "The password must contain at least one special character!",
            $_SESSION['createPasswordMessage']['password']
        )) !== false) {
            unset($_SESSION['createPasswordMessage']['password'][$arrayElement]);
        }
    }

    if ($sanitizedPassword !== $sanitizedPasswordConfirmation) {
        $_SESSION['createPasswordMessage']['passwordConfirmation'] = "Passwords do not match!";
    } else {
        unset($_SESSION['createPasswordMessage']['passwordConfirmation']);
    }

    if (
        empty($_SESSION['createPasswordMessage']['passwordConfirmation']) &&
        empty($_SESSION['createPasswordMessage']['password'])
    ) {
        $passwordResetCode = PasswordResetCode::retrievePasswordResetTokenByCode($code, $dbconn);

        $userId = $passwordResetCode->getUserId();
        $user = User::retrieveUser("SELECT * FROM users WHERE UserId = '$userId'", $dbconn);
        $encryptedPassword = password_hash($sanitizedPassword, PASSWORD_DEFAULT);
        $user->setPassword($encryptedPassword);
        $user->updatePassword($userId, $dbconn);
        $_SESSION['createPasswordMessage']['success'] = "<h1>Password has been successfully reset! <a class=\".link\" href=\"login.php\">Log in</a></h1>";
        $dbconn = null;
        header("location: create-password.php?code=$code");
        exit();
    } else {
        $dbconn = null;
        header("location: create-password.php?code=$code");
        exit();
    }
} else {
    $_SESSION['createPasswordMessage']['general'] = "<span class=\"errorMessage\">Please fill in all the fields.</span>";
    header("location: create-password.php?code=$code");
    exit();
}
