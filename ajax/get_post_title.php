<?php
require('../includes/objects.php');
require('../includes/dbconnection.php');

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$postId = $_POST['postId'];
$post = Post::retrievePost("SELECT * FROM Posts WHERE PostId = '$postId'", $dbconn);

if($post != null){
    echo $post[0]->getTitle();
}
else{
    echo "Hej";
}

$dbconn = null;
?>