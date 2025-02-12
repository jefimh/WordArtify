<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$postId = $_POST['postId'];
$userId = $_SESSION['sessionId'];

$like = Like::retrieveLikeByUser("SELECT * FROM likes WHERE PostId = '$postId' AND UserId = '$userId'", $dbconn);
$dislike = Dislike::retrieveDislikeByUser("SELECT * FROM dislikes WHERE UserId = '$userId' AND PostId = '$postId'", $dbconn);
$isDisliked = null;

if($like != null && $dislike == null){
    $like->delete($dbconn);

    $now = date('Y-m-d H:i:s');
    $dislike = new Dislike($postId, $userId, $now);
    $dislike->saveToTable($dbconn);
    $isDisliked = true;
}
else if($like == null && $dislike != null){
    $dislike->delete($dbconn);
    $isDisliked = false;
}
else {
    $now = date('Y-m-d H:i:s');
    $dislike = new Dislike($postId, $userId, $now);
    $dislike->saveToTable($dbconn);
    $isDisliked = true;
}

$likeCount = Like::retrieveRows("SELECT * FROM likes WHERE PostId = '$postId'", $dbconn);
$dislikeCount = Dislike::retrieveRows("SELECT * FROM dislikes WHERE PostId = '$postId'", $dbconn);
$dbconn = null;

$response = array("likeCount" => $likeCount, "dislikeCount" => $dislikeCount, "isDisliked" => $isDisliked);
echo json_encode($response);
?>