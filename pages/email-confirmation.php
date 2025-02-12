<?php
if (isset($_GET['code']) && !empty($_GET['code'])) {
    require('../includes/dbconnection.php');
    require('../includes/objects.php');
    session_start();

    $pageOpenedAt = time();
    $verificationCode = $_GET['code'];

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    }

    $user = User::retrieveUserByVerificationCode($verificationCode, $dbconn);

    date_default_timezone_set('Europe/Stockholm');
    $pageOpenedAt = time();

    if ($user != null) {
        $registeredAt = $user->getRegistrationTimestamp($dbconn);
        $registrationTimeInSeconds = strtotime($registeredAt);
        $minutesSinceRegistration = ($pageOpenedAt - $registrationTimeInSeconds) / 60;

        if ($minutesSinceRegistration < 15) {
            $user->verify($dbconn);
            echo "<span>You have successfully been verified. You can now close this tab
            and log back into your account. Automatically redirecting to login page in 3 seconds</span>";
            header( "refresh:3; url=login.php" );
        }
        else{
            $user->delete($dbconn);
            echo "<span>The verification link has expired :(</span>";
        }
    } else {
        echo "Error!";
    }
}
