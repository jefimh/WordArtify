  <?php
  session_start();
  if (isset($_SESSION['sessionId'])) {
    header('Location: home.php');
  }
  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Reset password</title>
    <link rel="stylesheet" href="../assets/css/reset-password.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
    <script src="../assets/js/reset-password.js" type="text/javascript"></script>
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
        <form method="POST" action="reset-password-view.php">
          <h1>Reset password</h1>
          <label for="email" id="email">Enter the registered account email for password reset</label>
          <input type="text" id="email" class="input" name="email" placeholder="Account email">
          <button id="formSubmit" name="submit" type="submit">Send link</button>
          <?php
          if (isset($_SESSION['resetPasswordMessage'])) {
            $message = $_SESSION['resetPasswordMessage'];
            unset($_SESSION['resetPasswordMessage']);
            echo $message;
          }
          ?>
        </form>
      </div>
    </div>
  </body>

  </html>