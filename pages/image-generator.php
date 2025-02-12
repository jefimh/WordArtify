<?php
require('../includes/session.php');
require('../includes/methods.php');
require('../includes/objects.php');
require('../includes/dbconnection.php');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>WordArtify - Text-to-Image Generator</title>
  <link rel="stylesheet" href="../assets/css/image-generator.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
  <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
  <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
  <script src="../assets/js/image-generator.js"></script>
</head>

<body>
  <header>
    <nav class="nav-container">
      <a href="landing.php" class="logo"><img src="../assets/images/logo-nobg.png" alt="Image of logo"></a>
      <ul class="nav-menu">
        <li><a href="home.php">Home</a></li>
        <li><a href="image-generator.php" class="selected">Image Generator</a></li>
        <li><a href="create-post.php">Create Post</a></li>
        <li><a href="registration.php">Registration</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>
  <form class="form-container" id="imageGenerationForm" method="post">
    <label for="prompt">
      <h2>Enter prompt to generate image:</h2>
    </label>
    <br>
    <?php
    if (isset($_SESSION['prompt']) && !empty($_SESSION['prompt'])) {
      $sanitizedPrompt = $_SESSION['prompt'];
      echo '<textarea class="input" name="prompt" id="textInput" cols="70" rows="5" placeholder="Descriptive prompt example: portrait of hulk in flat design art, masterpiece, award winning...">' . $sanitizedPrompt .  '</textarea>';
    } else {
      echo '<textarea class="input" name="prompt" id="textInput" cols="70" rows="5" placeholder="Descriptive prompt example: portrait of hulk in flat design art, masterpiece, award winning..."></textarea>';
    }

    unset($_SESSION['prompt']);
    ?>
    <br>
    <button type="submit" id="generateImageButton" onclick="handleImageGeneration()" name="submit">Generate Image</button>
    <div id="imageGenerationMessage"></div>
    <br>
    <?php
    if (isset($_SESSION['imgSrc'])) {
      $imgSrc = $_SESSION['imgSrc'];
      echo '<h2>Generated Image:</h2>';
      echo '<img src="' . $imgSrc . '" alt="Generated Image"><br>';
    }
    ?>
    <?php
    if (isset($_POST['submit'])) {
      if (isset($_POST['prompt']) && !empty($_POST['prompt'])) {
        $prompt = $_POST['prompt'];
        $specialCharsPattern = "/[^A-Za-z0-9\s]/";
        $promptWithoutSpecialChars = preg_replace($specialCharsPattern, "", $prompt);
        $sanitizedPrompt = sanitizeInput($promptWithoutSpecialChars);
        $_POST['prompt'] = null;

        echo "<h2>Your image is on the way! DO NOT refresh the page.</h2>";

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.prodia.com/v1/job",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\"negative_prompt\":\"nsfw\",\"prompt\":\"$sanitizedPrompt\"}",
          CURLOPT_HTTPHEADER => [
            "X-Prodia-Key: a63d81c2-ad42-43f7-b06a-f826e7fc98b2",
            "accept: application/json",
            "content-type: application/json"
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $decodedResponse = json_decode($response, true);
        }

        $jobId = $decodedResponse['job'];
        echo "Jobid: $jobId<br>";

        curl_close($curl);

        $status = "";

        // loopa tills status är lika med "succeeded"
        while ($status != "succeeded") {

          $curl = curl_init();

          curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.prodia.com/v1/job/$jobId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
              "X-Prodia-Key: a63d81c2-ad42-43f7-b06a-f826e7fc98b2",
              "accept: application/json"
            ],
          ]);

          $response = curl_exec($curl);
          $err = curl_error($curl);

          curl_close($curl);

          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
            $decodedResponse = json_decode($response, true);
            $status = $decodedResponse['status'];

            if ($status == "succeeded") {
              $imgSrc = $decodedResponse['imageUrl'];

              if (session_status() == PHP_SESSION_NONE) {
                session_start();
              }

              $_SESSION['imgSrc'] = $imgSrc;
              $_SESSION['prompt'] = $sanitizedPrompt;
              $_SESSION['imageGenerated'] = true;
              header("Refresh:0");
            } else {
              $_SESSION['imageGenerated'] = false;
            }
          }
        }

        // vänta en sekund mellan begäran. 
        sleep(1);
      }


      //spara genererade bilden till kontot. 

      if (isset($_SESSION['imgSrc'])) {
        $imgSrc = $_SESSION['imgSrc'];

        //extrahera bildnamnet/id:n
        $imgfileName = basename($imgSrc);
        //Hämta filnamnet utan .png ändelsen. 
        $imgId = pathinfo($imgfileName, PATHINFO_FILENAME);

        echo '<button type="submit" id="submitButton" onclick="disableButton(this)" name="saveImage">Save image to account</button>';

        function image_exists_on_server($imgId)
        {
          $folder = '../UserSavedImages/Users/' . $_SESSION['username'] . '/';

          //Titta genom alla filer i mappen som innehåller strängen $imgId. 
          $files = glob($folder . '/*' . $imgId . '*');

          if (count($files) > 0) {
            return true;
          }

          return false;
        }

        function save_image_to_table($filePath, $sanitizedPrompt)
        {
          require('../includes/dbconnection.php');

          try {
            $sqlUsersSavedImages = "INSERT INTO users_saved_images (UserId, ImageDirectoryPath, generatorPrompt, SavedAt) 
    VALUES (?, ?, ?, now())";

            $stmt = $dbconn->prepare($sqlUsersSavedImages);
            $data = array($_SESSION['sessionId'], $filePath, $sanitizedPrompt);
            $stmt->execute($data);
          } catch (PDOException $e) {
            echo $sqlUsersSavedImages . "<br>" . $e->getMessage();
          }
        }

        function save_image_to_server($imgId, $imgSrc, $sanitizedPrompt)
        {
          $folderPath = '../UserSavedImages/Users/' . $_SESSION['username'];

          if (!is_dir($folderPath)) {
            mkdir($folderPath);
          }

          if (!image_exists_on_server($imgId)) {
            $fileName = $imgId . time() . '.png'; //anger filnamnet och .png, vilket i det här fallet time() för att namnet ska vara unikt. 
            $filePath = '../UserSavedImages/Users/' . $_SESSION['username'] . '/' . $fileName; // filsökvägen där bilden ska sparas.

            $imageData = file_get_contents($imgSrc);
            // use file_put_contents() sparar bilden till server-mappen
            file_put_contents($filePath, $imageData);

            save_image_to_table($filePath, $sanitizedPrompt);

            echo "Image successfully saved to your account!";
          } else {
            echo "Image already saved!";
          }
        }

        if (isset($_POST['saveImage'])) {
          save_image_to_server($imgId, $imgSrc, $sanitizedPrompt);
        }
      }
    }
    ?>
    <br>
    <h2>Saved images</h2>
  </form>
  <div class="image-gallery">
    <?php
    $userId = $_SESSION['sessionId'];
    $userSavedImages = UserSavedImage::retrieveAllSavedImagesByUser($userId, $dbconn);

    if ($userSavedImages != null) {
      foreach (array_reverse($userSavedImages) as $image) {
        echo '<img class="image-item" src="' . $image->getImageDirectoryPath() . '" alt="' . $image->getGeneratorPrompt() . '">';
      }
      $dbconn = null;
    }
    ?>
  </div>
</body>

</html>