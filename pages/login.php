  <?php
  session_start();
  if (isset($_SESSION['sessionId'])) {
    header('Location: home.php');
  }
  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
    <script src="../assets/js/login.js" type="text/javascript"></script>
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
          <li><a href="login.php" class="selected">Login</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    <div class="container">
      <div class="form-section">
        <form method="POST" action="login-view.php">
          <h1>Login</h1>
          <label for="usernameOrEmail" id="usernameOrEmailLabel">Enter username or email</label>
          <div class="input-section">
            <i class="fas fa-user icon"></i>
            <?php
            if (isset($_SESSION['tempUsername'])) {
              $username = $_SESSION['tempUsername'];

              if (isset($_SESSION['usernameError']) && $_SESSION['usernameError']) {
                echo "<input type=\"text\" id=\"usernameOrEmail\" class=\"error\" name=\"usernameOrEmail\" value=\"$username\">";
              } else {
                echo "<input type=\"text\" id=\"usernameOrEmail\" class=\"input\" name=\"usernameOrEmail\" value=\"$username\">";
              }
            } else {
              if (isset($_SESSION['usernameError']) && $_SESSION['usernameError']) {
                echo "<input type=\"text\" id=\"usernameOrEmail\" placeholder=\"Enter username or email\" class=\"error\" name=\"usernameOrEmail\">";
              } else {
                echo "<input type=\"text\" id=\"usernameOrEmail\" placeholder=\"Enter username or email\" class=\"input\" name=\"usernameOrEmail\">";
              }
            }
            ?>
          </div>
          <label for="password">Password <a href="reset-password.php" class="link">Forgot password? Reset here</a></label>
          <div class="input-section">
            <i class="fas fa-lock icon"></i>
            <?php
            if (isset($_SESSION['tempPassword'])) {
              $password = $_SESSION['tempPassword'];

              if (isset($_SESSION['passwordError']) && $_SESSION['passwordError']) {
                echo "<input type=\"password\" id=\"password\" class=\"error\" name=\"password\"
                placeholder=\"Enter your password\" value=\"$password\">";
              } else {
                echo "<input type=\"password\" id=\"password\" name=\"password\"
                placeholder=\"Enter your password\" class=\"input\" value=\"$password\">";
              }
            } else {
              if (isset($_SESSION['passwordError']) && $_SESSION['passwordError']) {
                echo "<input type=\"password\" id=\"password\" class=\"error\" name=\"password\"
                placeholder=\"Enter your password\">";
              } else {
                echo "<input type=\"password\" id=\"password\" name=\"password\"
                placeholder=\"Enter your password\" class=\"input\">";
              }
            }
            ?>
            <i id="eye" class="eye fa-sharp fa-solid fa-eye" onclick="handlePasswordVisibility()"></i>
          </div>
          <button id="formSubmit" name="submit" type="submit">Login</button>
          <a class="link" href="registration.php">Don't have an account? Sign up here</a>
          <?php
          if (isset($_SESSION['loginErrorMessage']) && !empty($_SESSION['loginErrorMessage'])) {
            $error = $_SESSION['loginErrorMessage'];

            echo "<span class=\"errorMessage\">$error</span>";
          }
          ?>
        </form>
      </div>
      <div class="image-section">
        <img id="image" src="../assets/images/login-page-large.png" alt="Login page image">
      </div>
    </div>
  </body>

  </html>