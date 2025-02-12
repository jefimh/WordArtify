<?php
require('../includes/session.php');
require('../includes/methods.php');
require('../includes/objects.php');
require('../includes/dbconnection.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Word Artify - Create Post</title>
    <link rel="stylesheet" href="../assets/css/create-post.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
    <script src="../assets/js/create-post.js"></script>
</head>

<body>
    <header>
        <nav class="nav-container">
            <a href="landing.php" class="logo"><img src="../assets/images/logo-nobg.png" alt="Image of logo"></a>
            <ul class="nav-menu">
                <li><a href="home.php">Home</a></li>
                <li><a href="image-generator.php">Image Generator</a></li>
                <li><a href="create-post.php" class="selected">Create Post</a></li>
                <li><a href="registration.php">Registration</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <form class="form-container" method="post" action="create-post-view.php">
        <label for="title">
            <h2>Create post</h2>
        </label>
        <br>
        <textarea class="input" name="title" id="textInput" cols="50" placeholder="Post title..."></textarea>
        <br>
        <h2>Choose from your saved/uploaded images below or upload an external image: <label class="button" for="upload-photo">Upload Image</label></h2>
        <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" id="upload-photo" onchange="uploadImage()">
        <?php
        if (isset($_SESSION['createPostMessage'])) {
            $message = $_SESSION['createPostMessage'];
            unset($_SESSION['createPostMessage']);
            echo '<br><div id="response">' . $message . '</div>';
        }
        ?>
        <br>
        <?php
        $userId = $_SESSION['sessionId'];
        $userSavedImages = UserSavedImage::retrieveAllSavedImagesByUser($userId, $dbconn);

        if ($userSavedImages != null) {
            echo '<h2>Saved images</h2><div class="image-gallery">';

            foreach (array_reverse($userSavedImages) as $image) {
                if ($image->getGeneratorPrompt() != "Externally uploaded file") {
                    echo '<img class="image-item" src="' . $image->getImageDirectoryPath() . '" alt="' . $image->getGeneratorPrompt() . '">';
                }
            }

            echo '</div>';
        } else {
            echo "<h2>No saved images found. Go to our<a class=\"link\" href=\"image-generator\"> image generator</a> to create an image!</h2>";
        }
        ?>
        <br>
        <div class="uploaded-images-container">

        </div>
        <?php
        $userId = $_SESSION['sessionId'];
        $userSavedImages = UserSavedImage::retrieveAllSavedImagesByUser($userId, $dbconn);

        if ($userSavedImages != null) {
            $html = '<h2>Uploaded images</h2><div class="image-gallery 2">';

            $imageFound = false;
            foreach (array_reverse($userSavedImages) as $image) {
                if ($image->getGeneratorPrompt() == "Externally uploaded file") {
                    $html .= '<img class="image-item" src="' . $image->getImageDirectoryPath() . '" alt="' . $image->getGeneratorPrompt() . '">';
                    $imageFound = true;
                }
            }

            $html .= '</div>';

            if (!$imageFound) {
                $html = null;
            }

            echo $html;
        }
        $dbconn = null;
        ?>
        </div>
        <input type="hidden" name="imgSrc">
        <button type="submit" name="submit">Publish</button>
        <br>
    </form>

    <script>
        const images = document.querySelectorAll(".image-item");
        const hiddenInput = document.querySelector('input[name="imgSrc"]');

        images.forEach((image) => {
            image.addEventListener("click", () => {
                images.forEach((image) => {
                    image.classList.remove("selected");
                });
                image.classList.add("selected");
                hiddenInput.value = image.src;
            });
        });
    </script>
</body>

</html>