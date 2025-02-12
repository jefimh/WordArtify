<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['sessionId'];
$postId = $_POST['postId'];
$comments = Comment::retrieveComments("SELECT * FROM comments WHERE PostId = '$postId'", $dbconn);

$html = '';
if($comments != null){
    foreach ($comments as $comment) {
        $commentUserId = $comment->getUserId();
        $commentId = $comment->getCommentId($dbconn);
        $user = User::retrieveUser("SELECT * FROM users WHERE UserId = '$commentUserId'", $dbconn);
    
        if($userId == $commentUserId){
            $html .=
            '<li id="comment-' . $comment->getCommentId($dbconn) . '">
                <span class="comment-author">' . $user->getUsername() . ':</span>
                <span class="comment-text">' . $comment->getComment() . '</span>
                <i class="fa-solid fa-xmark" onclick="deleteComment(\'comment-'. $comment->getCommentId($dbconn) . '\')"></i>
            </li>';
        }
        else{
            $html .=
            '<li>
                <span class="comment-author">' . $user->getUsername() . ':</span>
                <span class="comment-text">' . $comment->getComment() . '</span>
            </li>';
        }
    }
}

$dbconn = null;
echo $html;
?>