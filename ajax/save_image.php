<?php
session_start();
require('../includes/objects.php');
require('../includes/dbconnection.php');
require('../includes/methods.php');

if ($_POST['imgSrc'] != "" && !empty($_POST['imgSrc'])) {
    $imgSrc = $_SESSION['imgSrc'];
    $userId = $_SESSION['sessionId'];
    $postId = $_POST['postId'];

    $post = Post::retrievePost("SELECT * FROM posts WHERE PostId = '$postId'", $dbconn);
    $imageDirectoryPath = $post[0]->getImageDirectoryPath();
    $userSavedImage = UserSavedImage::retrieveImageByImageDirectoryPath($userId, $imageDirectoryPath, $dbconn);
    $generatorPrompt = $post[0]->getGeneratorPrompt();
    $imageName = basename($imageDirectoryPath);

    if ($userSavedImage == null) {
        $folderPath = '../UserSavedImages/Users/' . $_SESSION['username'];

        if (!is_dir($folderPath)) {
            mkdir($folderPath);
        }

        $newFolderPath = $folderPath . '/' . $imageName;
        $saveImage = new UserSavedImage($userId, $imageDirectoryPath, $generatorPrompt, "");
        $saveImage->saveToTable($dbconn);

        if (copy($imageDirectoryPath, $newFolderPath)) {
            echo "Image saved!";
        } 
        $dbconn = null;
    }
    else{
        echo "Image already saved!";
    }
}
