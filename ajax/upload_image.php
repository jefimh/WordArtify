<?php
require("../includes/dbconnection.php");
require("../includes/objects.php");
session_start();

$text = $_FILES["image"]["name"]; 
$userId = $_SESSION['sessionId'];
$user = User::retrieveUser("SELECT * FROM Users WHERE UserId = '$userId'", $dbconn);

if (isset($_FILES["image"]["tmp_name"]) && $_FILES["image"]["tmp_name"] != "") 
{
    $folderPath = '../UserSavedImages/Users/' . $_SESSION['username'];
    if (!is_dir($folderPath)) {
        mkdir($folderPath);
    }

    $filePath = $folderPath . '/' . $text;

    copy($_FILES["image"]["tmp_name"],  $folderPath . '/' . $text);
    $userSavedImage = new UserSavedImage($userId, $filePath, "Externally uploaded file", "");
    $userSavedImage->saveToTable($dbconn);
    $dbconn = null;
    unlink($_FILES["image"]["tmp_name"]);
    echo "The file " . htmlspecialchars($text) . " has been uploaded.";
}
else {
    echo "Error uploading file.";
}
