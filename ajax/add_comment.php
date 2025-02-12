<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');
require('../includes/methods.php');

if($_POST['comment'] != "" && !empty($_POST['comment'])){
    $comment = $_POST['comment'];
    $commentContainsLetters = preg_match('/[a-zA-Z]/', $comment);

    if($commentContainsLetters){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $postId = $_POST['postId'];
        $sanitizedComment = sanitizeInput($_POST['comment']);
        $userId = $_SESSION['sessionId'];
        $now = date('Y-m-d H:i:s');
        $comments = new Comment($postId, $userId, $sanitizedComment, $now);
        $comments->saveToTable($dbconn);

        $comments = Comment::retrieveComments("SELECT * FROM comments WHERE PostId = '$postId' ORDER BY CreatedAt DESC LIMIT 1", $dbconn);
        
        $html = '';
        if($comments != null){
            foreach ($comments as $comment) {
                $userId = $comment->getUserId();
                $user = User::retrieveUser("SELECT * FROM users WHERE UserId = '$userId'", $dbconn);
                
                $html .=
                '<li id="comment-' . $comment->getCommentId($dbconn) . '">
                    <span class="comment-author">' . $user->getUsername() . ':</span>
                    <span class="comment-text">' . $comment->getComment() . '</span>
                    <i class="fa-solid fa-xmark" onclick="deleteComment(\'comment-'. $comment->getCommentId($dbconn) . '\')"></i>
                </li>';
            }
        }
        
        $dbconn = null;
        echo $html;
    }
    else{
        echo null;
    }
}
?>