<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require('../includes/dbconnection.php');
require('../includes/objects.php');
require('../includes/methods.php');

if (isset($_POST["submit"])) {
    $registrationErrors = array();

    if (
        isset($_POST['username']) && $_POST['username'] != ""
        && isset($_POST['email']) && $_POST['email'] != ""
        && isset($_POST['password']) && $_POST['password'] != ""
        && isset($_POST['passwordConfirmation']) && $_POST['passwordConfirmation'] != ""
    ) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['passwordConfirmation'];

        $_SESSION['registrationUsername'] = $username;
        $_SESSION['registrationEmail'] = $email;
        $_SESSION['registrationPassword'] = $password;
        $_SESSION['passwordConfirmation'] = $passwordConfirmation;

        $sanitizedUsername = sanitizeInput($username);
        $sanitizedPassword = sanitizeInput($password);
        $sanitizedPasswordConfirmation = sanitizeInput($passwordConfirmation);
        $encryptedPassword = password_hash($sanitizedPassword, PASSWORD_DEFAULT);
        $sanitizedEmail = sanitizeInput($email);

        //Säkerställer ifall användarnamnet redan finns
        $user = User::retrieveUserByUsername($sanitizedUsername, $dbconn);

        if (empty($sanitizedUsername) || empty($sanitizedEmail) || empty($sanitizedPassword) || empty($passwordConfirmation)) {
            $_SESSION['registrationErrors']['general'] = "Fill in all fields!";
        } else {
            unset($_SESSION['registrationErrors']['general']);
        }

        if ($user != null) {
            $_SESSION['registrationErrors']['username'] = "Username already exists, please choose another one!";
        } else {
            unset($_SESSION['registrationErrors']['username']);
        }

        if (preg_match('/[^a-zA-Z0-9\s]/', $sanitizedUsername)) {
            $_SESSION['registrationErrors']['username'] = "Username cannot contain special characters!";
        } else {
            unset($_SESSION['registrationErrors']['general']);
        }

        if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['registrationErrors']['email'] = "Invalid email address!";
        } else {
            unset($_SESSION['registrationErrors']['email']);
        }

        $_SESSION['registrationErrors']['password'] = array();
        if (strlen($sanitizedPassword) < 8) {
            $_SESSION['registrationErrors']['password'][] = "Password must be at least 8 characters long!";
        } else {
            if (($arrayElement = array_search(
                "Password must be at least 8 characters long!",
                $_SESSION['registrationErrors']['password']
            )) !== false) {
                unset($_SESSION['registrationErrors']['password'][$arrayElement]);
            }
        }

        if (!preg_match('/[a-zA-Z]/', $sanitizedPassword)) {
            $_SESSION['registrationErrors']['password'][] = "The password must contain at least one letter!";
        } else {
            if (($arrayElement = array_search(
                "The password must contain at least one letter!",
                $_SESSION['registrationErrors']['password']
            )) !== false) {
                unset($_SESSION['registrationErrors']['password'][$arrayElement]);
            }
        }

        if (!preg_match('/\d/', $sanitizedPassword)) {
            $_SESSION['registrationErrors']['password'][] = "The password must contain at least one digit!";
        } else {
            if (($arrayElement = array_search(
                "The password must contain at least one digit!",
                $_SESSION['registrationErrors']['password']
            )) !== false) {
                unset($_SESSION['registrationErrors']['password'][$arrayElement]);
            }
        }

        if (!preg_match('/[^a-zA-Z0-9\s]/', $sanitizedPassword)) {
            $_SESSION['registrationErrors']['password'][] = "The password must contain at least one special character!";
        } else {
            if (($arrayElement = array_search(
                "The password must contain at least one special character!",
                $_SESSION['registrationErrors']['password']
            )) !== false) {
                unset($_SESSION['registrationErrors']['password'][$arrayElement]);
            }
        }

        if ($sanitizedPassword !== $sanitizedPasswordConfirmation) {
            $_SESSION['registrationErrors']['passwordConfirmation'] = "Passwords do not match!";
        } else {
            unset($_SESSION['registrationErrors']['passwordConfirmation']);
        }

        if (
            empty($_SESSION['registrationErrors']['general']) &&
            empty($_SESSION['registrationErrors']['username']) &&
            empty($_SESSION['registrationErrors']['email']) &&
            empty($_SESSION['registrationErrors']['passwordConfirmation']) &&
            empty($_SESSION['registrationErrors']['password'])
        ) {
            $verificationCode = md5(uniqid(rand(), true)) . time();
            $verificationLink = "http://php-projekt.test/pages/email-confirmation.php?code=" . $verificationCode;
            mail($sanitizedEmail, "WordArtify Registration Verification Link", "<a href=\"$verificationLink\">Click me to verify</a>");

            unsetSessionVariables(
                'registrationErrors',
                'registrationUsername',
                'registrationEmail',
                'registrationPassword',
                'passwordConfirmation'
            );

            $_SESSION['message'] = "Verification link sent to: <a>$sanitizedEmail</a><br> Please check your email!";
            $user = new User($sanitizedUsername, $encryptedPassword, $sanitizedEmail, $verificationCode, 0);
            $user->saveToTable($dbconn);
            $dbconn = null;
            header("location: registration.php");
            exit();
        } else {
            header("location: registration.php");
            exit();
        }
    } else {
        $_SESSION['registrationErrors']['general'] = "Please fill in all the fields!";
        header("location: registration.php");
        exit();
    }
}
