<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');
session_start();

$postId = $_POST['postId'];
$userId = $_SESSION['sessionId'];
$like = Like::retrieveLikes("SELECT * FROM likes WHERE PostId = '$postId'", $dbconn);
$userLike = Like::retrieveLikes("SELECT * FROM likes WHERE PostId = '$postId' AND UserId = '$userId'", $dbconn);
$isLiked = null;

if($like != null){
    $amountOfLikes = count($like);
}
else{
    $amountOfLikes = 0;
}

if($userLike != null){
    $isLiked = true;
}
else{
    $isLiked = false;
}

$response = array("amountOfLikes" => $amountOfLikes, "isLiked" => $isLiked);
echo json_encode($response);
$dbconn = null;
?>