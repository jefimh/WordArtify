<?php
require('../includes/methods.php');
session_start();
if (isset($_SESSION['sessionId'])) {
  header('Location: home.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Create new password</title>
  <link rel="stylesheet" href="../assets/css/create-password.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
  <script src="../assets/js/create-password.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
</head>

<body>
  <header>
    <nav class="nav-container">
      <a href="landing.php" class="logo"><img src="../assets/images/logo-nobg.png" alt="Image of logo"></a>
      <ul class="nav-menu">
        <li><a href="home.php">Home</a></li>
        <li><a href="image-generator.php">Image Generator</a></li>
        <li><a href="create-post.php">Create Post</a></li>
        <li><a href="registration.php">Registration</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <div class="container">
    <?php
    if (isset($_GET['code']) && !empty($_GET['code'])) {
      require('../includes/dbconnection.php');
      require('../includes/objects.php');

      date_default_timezone_set('Europe/Stockholm');
      $pageOpenedAt = time();
      $resetCode = $_GET['code'];

      $passwordResetCode = PasswordResetCode::retrievePasswordResetTokenByCode($resetCode, $dbconn);

      if ($passwordResetCode != null && !isset($_SESSION['createPasswordMessage']['success'])) {
        $createdAt = $passwordResetCode->getCreatedAt();
        $createdAtInSeconds = strtotime($createdAt);
        $minutesSinceCreatedAt = ($pageOpenedAt - $createdAtInSeconds) / 60;

        if ($minutesSinceCreatedAt < 15) {
          echo '
              <div class="form-section">
                <form method="POST" action="create-password-view.php">
                  <h1>Reset password</h1>
                  <label for="password" id="passwordLabel">Enter new password</label>';
          if (isset($_SESSION['createPasswordMessage']['password'])) {
            foreach ($_SESSION['createPasswordMessage']['password'] as $passwordError) {
              echo '<span class="errorMessage">' . $passwordError . '</span>';
            }
          }
          echo '<div class="input-section">
                    <i class="fas fa-lock icon"></i>';

          if (isset($_SESSION['createPasswordMessage']['password']) && !empty($_SESSION['createPasswordMessage']['password'])) {

            if (isset($_SESSION['createPassword'])) {
              $password = $_SESSION['createPassword'];
              echo '<input type="password" id="password" class="error" name="password" placeholder="New password" value="' . $password  . '">';
            }
          } else {
            if (isset($_SESSION['createPassword'])) {
              $password = $_SESSION['createPassword'];
              echo '<input type="password" id="password" class="input" name="password" placeholder="New password" value="' . $password  . '">';
            } else {
              echo '<input type="password" id="password" class="input" name="password" placeholder="New password">';
            }
          }

          echo '<i id="eye" class="eye fa-sharp fa-solid fa-eye" onclick="handlePasswordVisibility()"></i>
                  </div>';

          if (isset($_SESSION['createPasswordMessage']['passwordConfirmation']) && !empty($_SESSION['createPasswordMessage']['passwordConfirmation'])) {
            $error = $_SESSION['createPasswordMessage']['passwordConfirmation'];
            echo '<span class="errorMessage">' . $error . '</span>';
          }

          echo '<div class="input-section">
                    <i class="fa-solid fa-check icon"></i>';

          if (isset($_SESSION['createPasswordMessage']['passwordConfirmation']) && !empty($_SESSION['createPasswordMessage']['passwordConfirmation'])) {

            if (isset($_SESSION['createPasswordConfirmation'])) {
              $passwordConfirmation = $_SESSION['createPasswordConfirmation'];
              echo '<input type="password" id="passwordConfirmation" class="error" name="passwordConfirmation" placeholder="Confirm new password" value="' . $passwordConfirmation  . '">';
            }
          } else {
            if (isset($_SESSION['createPasswordConfirmation'])) {
              $passwordConfirmation = $_SESSION['createPasswordConfirmation'];
              echo '<input type="password" id="passwordConfirmation" class="input" name="passwordConfirmation" placeholder="Confirm new password" value="' . $passwordConfirmation  . '">';
            } else {
              echo '<input type="password" id="passwordConfirmation" class="input" name="passwordConfirmation" placeholder="Confirm new password">';
            }
          }

          echo '</div>';

          if (isset($_SESSION['createPasswordMessage']['general']) && !empty($_SESSION['createPasswordMessage']['general'])) {
            $message = $_SESSION['createPasswordMessage']['general'];
            unset($_SESSION['createPasswordMessage']['general']);
            echo $message;
          } else if (isset($_SESSION['createPasswordMessage']['success']) && !empty($_SESSION['createPasswordMessage']['general'])) {
            $message = $_SESSION['createPasswordMessage']['success'];
            unset($_SESSION['createPasswordMessage']['success']);
            echo $message;
          }

          echo '<button id="formSubmit" name="submit" type="submit">Reset password</button>
          <input type="hidden" name="code" value="' . $resetCode . '">
                </form>
              </div>';
        } else {
          echo "<h1>The reset password link has expired :(</h1>";
          $passwordResetCode->delete($dbconn);
        }
      } else if (isset($_SESSION['createPasswordMessage']['success'])) {
        $message = $_SESSION['createPasswordMessage']['success'];
        unsetSessionVariables(
          'createPasswordMessage',
          'createPassword',
          'createPasswordConfirmation'
        );
        echo $message;
        $passwordResetCode->delete($dbconn);
        $dbconn = null;
      } else {
        echo "<h1>The reset password link has expired. <a class=\"link\" href=\"reset-password.php\">Reset password</a></h1>";

        if ($passwordResetCode != null) {
          $passwordResetCode->delete($dbconn);
          $dbconn = null;
        }
      }
    } else {
      header("Location: reset-password.php");
    }
    ?>
  </div>
</body>

</html>