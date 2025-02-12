<?php
session_start();

if (isset($_POST['submit'])) {
    if (
        isset($_POST['title']) && !empty($_POST['title'])
        && isset($_POST['imgSrc']) && !empty($_POST['imgSrc'])
    ) {
        require('../includes/methods.php');
        require('../includes/objects.php');
        require('../includes/dbconnection.php');

        $title = sanitizeInput($_POST['title']);
        $imgSrc = $_POST['imgSrc'];
        $localImgSrc =  preg_replace('#^http://[^/]+/#', '../', $imgSrc);
        $userId = $_SESSION['sessionId'];
        $userSavedImage = UserSavedImage::retrieveImageByImageDirectoryPath($userId, $localImgSrc, $dbconn);

        if ($userSavedImage != null) {
            $generatorPrompt = $userSavedImage->getGeneratorPrompt();
            $post = new Post($userId, $localImgSrc, $generatorPrompt, $title, "");
            $post->saveToTable($dbconn);
            $dbconn = null;
            $_SESSION['createPostMessage'] = "<h2>Image successfully uploaded. You can now find it in: <a class=\"link\" href=\"home.php\">Community</a></h2>";
            header("Location: create-post.php");
            exit();
        } else {
            $dbconn = null;
            header("Location: create-post.php");
            exit();
        }
    } else {
        $_SESSION['createPostMessage'] = "<h2 class=\"link\ class=\"errorMessage\">Please choose title and image to create post!</h2>";
        header("Location: create-post.php");
        exit();
    }
} else {
    header("Location: create-post.php");
    exit();
}
