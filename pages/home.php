<?php
require('../includes/session.php');
require('../includes/dbconnection.php');
require('../includes/objects.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/home.css">
    <title>WordArtify - Home page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/cd3c3266fa.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/logo-nobg-cropped-32x.png">
    <script src="../assets/js/home.js"></script>
</head>

<body>
    <header>
        <nav class="nav-container">
            <a href="landing.php" class="logo"><img src="../assets/images/logo-nobg.png" alt="Image of logo"></a>
            <ul class="nav-menu">
                <li><a href="home.php" class="selected">Home</a></li>
                <li><a href="image-generator.php">Image Generator</a></li>
                <li><a href="create-post.php">Create Post</a></li>
                <li><a href="registration.php">Registration</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="hero" id="hero-section">
        <div class="hero-background">
            <div class="hero-text">
                <?php
                $username = $_SESSION['username'];
                echo "<h1>Happy to see you today, $username!</h1>"
                ?>
            </div>
            <h2><a class="link" href="image-generator.php">Generate image</a></h2>
        </div>
    </div>
    <main>
        <div class="posts-container">
            <div class="posts-navbar">
                <nav class="feed-nav">
                    <h1 class="header">Community</h1>
                    <ul class="nav-menu">
                        <li>
                            <div class="input-section"><i class="icon fa fa-search"></i><input size="30" type="text" name="search" placeholder="search prompt/post title/username" class="input search-bar" id="searchBar"></div>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php
            $posts = Post::retrieveAllPosts("SELECT * FROM posts ORDER BY CreatedAt DESC", $dbconn);

            if ($posts != null) {
                foreach ($posts as $post) {
                    $postUserId = $post->getUserId();
                    $userId = $_SESSION['sessionId'];
                    $user = User::retrieveUser("SELECT * FROM Users WHERE UserId = '$postUserId'", $dbconn);
                    $username = $user->getUsername();

                    if ($postUserId == $userId) {
                        $html =
                            "<div class='card' id='" . $post->getPostId($dbconn) . "' onclick='displayPostContent(this)'>
                            <i class='fas fa-times card-delete' onclick='deleteCard(event, this)'></i>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
                    } else {
                        $html =
                            "<div class='card' id='" . $post->getPostId($dbconn) . "' ' onclick='displayPostContent(this)'>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
                    }

                    echo $html;
                }
            }
            ?>
        </div>
        <div class="popup-container">
            <div class="popup">
                <img id="popupImage" src="../assets/images/placeholder.jpg" alt="Post Image">
                <div class="post-details">
                    <h2 class="post-title">Popup Title</h2>
                    <br>
                    <p class="post-user" id="postedBy">Posted By: </p>
                    <br>
                    <p id="popupPrompt">Prompt: f8ejffj8je8j8fe</p>
                    <br>
                    <div class="likes">
                        <i onclick="handleLiking()" class="like-symbol fa fa-thumbs-up" aria-hidden="true"></i>
                        <span class="like-count"></span>
                        <i onclick="handleDisliking()" class="like-symbol fa fa-thumbs-down" aria-hidden="true"></i>
                        <span class="dislike-count"></span>
                        <i onclick="saveImage()" class="like-symbol fa-regular fa-floppy-disk"></i>
                        <div class="info-message"></div>
                    </div>
                    <div class="comments">
                        <h2>Comments</h2>
                        <ul class="comment-list">
                            <!-- <li>
                                <span class="comment-author"></span>
                                <span class="comment-text"></span>
                            </li> -->
                        </ul>
                        <br>
                        <input type="text" class="comment-input" id="commentText" name="comment-text" placeholder="Add comment...">
                        <button class="button" type="submit" onclick="addComment()">Add comment</button>
                    </div>
                </div>
                <i class='fas fa-times card-delete-popup' onclick='closePopup(); resetComments();'></i>
            </div>
        </div>
    </main>
    <script src="../assets/js/home.js"></script>
</body>

</html>