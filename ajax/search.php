<?php
session_start();
require('../includes/objects.php');
require('../includes/dbconnection.php');
require('../includes/methods.php');

if ($_POST['search'] != "" && !empty($_POST['search'])) {
    $search = sanitizeInput($_POST['search']);
    $searchContainsLetters = preg_match('/[a-zA-Z]/', $search);

    if ($searchContainsLetters) {
        $searchTerm = "%" . $search . "%";
        $posts = Post::searchPosts($searchTerm, $dbconn);

        if ($posts != null) {
            foreach ($posts as $post) {
                $postUserId = $post->getUserId();
                $userId = $_SESSION['sessionId'];
                $user = User::retrieveUser("SELECT * FROM users WHERE UserId = '$postUserId'", $dbconn);
                $username = $user->getUsername();

                if ($postUserId == $userId) {
                    $html =
                        "<div class='card' id='" . $post->getPostId($dbconn) . "' onclick='displayPostContent(this)'>
                            <i class='fas fa-times card-delete' onclick='deleteCard(event, this)'></i>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
                } else {
                    $html =
                        "<div class='card' id='" . $post->getPostId($dbconn) . "' onclick='displayPostContent(this)'>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
                }

                echo $html;
            }
        }
    } else {
        echo null;
    }
} else {
    $posts = Post::retrieveAllPosts("SELECT * FROM posts ORDER BY CreatedAt DESC", $dbconn);

    if ($posts != null) {
        foreach ($posts as $post) {
            $postUserId = $post->getUserId();
            $userId = $_SESSION['sessionId'];
            $user = User::retrieveUser("SELECT * FROM users WHERE UserId = '$postUserId'", $dbconn);
            $username = $user->getUsername();

            if ($postUserId == $userId) {
                $html =
                    "<div class='card' id='" . $post->getPostId($dbconn) . "' onclick='displayPostContent(this)'>
                            <i class='fas fa-times card-delete' onclick='deleteCard(event, this)'></i>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
            } else {
                $html =
                    "<div class='card' id='" . $post->getPostId($dbconn) . "' onclick='displayPostContent(this)'>
                            <img src='" . $post->getImageDirectoryPath() . "' alt='" . $post->getGeneratorPrompt() . "'>
                            <div class='card-content'>
                                <h3>" . $post->getTitle() . "</h3>
                                <h4><strong>Posted By: </strong>" . $username . "</h4>
                                <br>
                                <p><strong>Prompt: </strong>" . $post->getGeneratorPrompt() . "</p>
                            </div>
                        </div>";
            }

            echo $html;
        }
    }
}
$dbconn = null;
