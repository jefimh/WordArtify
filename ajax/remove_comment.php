<?php
session_start();

require('../includes/objects.php');
require('../includes/dbconnection.php');

if($_POST['commentId'] != "" && !empty($_POST['commentId'])){
    $commentIdElement = $_POST['commentId'];
    $commentId = preg_replace('/[^0-9]/', '', $commentIdElement);
    echo $commentId;

    $comment = Comment::delete("DELETE FROM comments WHERE CommentId = '$commentId'", $dbconn);
    $dbconn = null;
}
?>