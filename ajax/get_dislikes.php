<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$postId = $_POST['postId'];
$userId = $_SESSION['sessionId'];
$dislikes = Dislike::retrieveDislikes("SELECT * FROM dislikes WHERE PostId = '$postId'", $dbconn);
$userDislike = Dislike::retrieveDislikes("SELECT * FROM dislikes WHERE PostId = '$postId' AND UserId = '$userId'", $dbconn);
$isDisliked = null;

if($dislikes != null){
    $amountOfDislikes = count($dislikes);
}
else{
    $amountOfDislikes = 0;
}

if($userDislike != null){
    $isDisliked = true;
}
else{
    $isDisliked = false;
}


$response = array("amountOfDislikes" => $amountOfDislikes, "isDisliked" => $isDisliked);
echo json_encode($response);
$dbconn = null;
?>