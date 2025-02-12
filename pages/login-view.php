<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["submit"])) {
    if (isset($_POST['usernameOrEmail']) && !empty($_POST['usernameOrEmail'] &&
        isset($_POST['password']) && !empty($_POST['password']))) {
        require('../includes/dbconnection.php');
        require('../includes/objects.php');
        require('../includes/methods.php');

        $sanitizedUsernameOrEmail = sanitizeInput($_POST['usernameOrEmail']);
        $sanitizedPassword = sanitizeInput($_POST['password']);

        $_SESSION['tempUsername'] = $sanitizedUsernameOrEmail;
        $_SESSION['tempPassword'] = $sanitizedPassword;

        //om email är angiven
        if (filter_var($sanitizedUsernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $user = User::retrieveUserByEmail($sanitizedUsernameOrEmail, $dbconn);
        } else {
            $user = User::retrieveUserByUsername($sanitizedUsernameOrEmail, $dbconn);
        }

        if ($user != null) {
            if ($user->getVerificationStatus() != 0) {
                if (password_verify($sanitizedPassword, $user->getPassword())) {
                    $_SESSION['sessionId'] = $user->getUserId($dbconn);
                    $_SESSION['username'] = $user->getUsername();

                    unsetSessionVariables(
                        'usernameError',
                        'passwordError',
                        'tempUsername',
                        'loginErrorMessage',
                        'tempPassword'
                    );

                    header('Location: home.php');
                    exit();
                } else {
                    $_SESSION['passwordError'] = true;
                    $_SESSION['usernameError'] = false;
                    $_SESSION['loginErrorMessage'] = "Password is incorrect!";
                    header('Location: login.php');
                    exit();
                }
            } else {
                $_SESSION['loginErrorMessage'] = "Account is not verified!";
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['usernameError'] = true;
            $_SESSION['passwordError'] = true;
            $_SESSION['loginErrorMessage'] = "The given username or email is incorrect!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['usernameError'] = true;
        $_SESSION['passwordError'] = true;
        $_SESSION['loginErrorMessage'] = "Please fill in all fields!";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
