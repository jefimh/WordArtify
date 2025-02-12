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
$isLiked = null;

if($like == null && $dislike != null){
    $dislike->delete($dbconn);

    $now = date('Y-m-d H:i:s');
    $like = new Like($postId, $userId, $now);
    $like->saveToTable($dbconn);
    $isLiked = true;
}
else if($like != null && $dislike == null){
    $like->delete($dbconn);
    $isLiked = false;
}
else{
    $now = date('Y-m-d H:i:s');
    $like = new Like($postId, $userId, $now);
    $like->saveToTable($dbconn);
    $isLiked = true;
}

$likeCount = Like::retrieveRows("SELECT * FROM likes WHERE PostId = '$postId'", $dbconn);
$dislikeCount = Dislike::retrieveRows("SELECT * FROM dislikes WHERE PostId = '$postId'", $dbconn);


$dbconn = null;

$response = array("likeCount" => $likeCount, "dislikeCount" => $dislikeCount, "isLiked" => $isLiked);
echo json_encode($response);
?>