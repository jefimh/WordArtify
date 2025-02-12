<?php
session_start();

require('../includes/objects.php');
require('../includes/dbconnection.php');

if($_POST['postId'] != "" && !empty($_POST['postId'])){
    $postId = $_POST['postId'];
    echo $postId;

    $post = Post::retrievePost("SELECT * FROM posts WHERE PostId = '$postId'", $dbconn);
    $post[0]->delete($dbconn);
    $dbconn = null;
}
?>