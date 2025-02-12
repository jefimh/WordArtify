<?php
require("../includes/dbconnection.php");
require("../includes/objects.php");
session_start();

$userId = $_SESSION['sessionId'];
$userSavedImages = UserSavedImage::retrieveAllSavedImagesByUser($userId, $dbconn);

if ($userSavedImages != null) {
    foreach (array_reverse($userSavedImages) as $image) {
        if ($image->getGeneratorPrompt() == "Externally uploaded file") {
            $html .= '<img class="image-item" src="' . $image->getImageDirectoryPath() . '" alt="' . $image->getGeneratorPrompt() . '">';
        }
    }

    $html .= '</div>';
    echo $html;
}
$dbconn = null;
?>