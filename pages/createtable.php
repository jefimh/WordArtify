<?php
include('../includes/dbconnection.php');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Create table</title>
</head>

<body>
    <?php
    try {
        $sqlUsers = "CREATE TABLE users(
            UserId int primary key auto_increment, 
            Username varchar(15) not null,
            Password varchar(255) not null,
            Email varchar(255) not null,
            VerificationCode varchar(255) not null,
            IsVerified boolean not null,
            RegisteredAt timestamp default current_timestamp
            )";

        $dbconn->exec($sqlUsers);
        echo "Table \"Users\" created successfully";
    } catch (PDOException $e) {
        echo $sqlUsers . "<br>" . $e->getMessage();
    }

    try {
        $sqlPosts = "CREATE TABLE posts(
            PostId int primary key auto_increment,
            UserId int not null,
            ImageDirectoryPath varchar(255) not null,
            GeneratorPrompt TEXT not null,
            Title varchar(255) not null,
            CreatedAt timestamp default current_timestamp,
            /* 
            FOREIGN KEY: anropar till primär nyckel (primary key) från en annan tabell. 
            I detta fall posts och users tabellen (våra REFERENCES) ON DELETE CASCADE: 
            Raderar respektive rad från tabellen när den referade raden raderas i tabellen som anropas. 
            */
            foreign key (UserId) references users(UserId) on delete cascade
            )";

        $dbconn->exec($sqlPosts);
        echo "Table \"Posts\" created successfully";
    } catch (PDOException $e) {
        echo $sqlPosts . "<br>" . $e->getMessage();
    }

    try {
        $sqlUsersSavedImages = "CREATE TABLE users_saved_images(
            ImageId int primary key auto_increment,
            UserId int not null,
            ImageDirectoryPath varchar(255) not null,
            GeneratorPrompt TEXT not null,
            SavedAt timestamp default current_timestamp,
            foreign key (UserId) references users(UserId) on delete cascade
            )";

        $dbconn->exec($sqlUsersSavedImages);
        echo "Table \"users_saved_images\" created successfully";
    } catch (PDOException $e) {
        echo $sqlUsersSavedImages . "<br>" . $e->getMessage();
    }

    try {
        $sqlComments = "CREATE TABLE comments(
            CommentId int primary key auto_increment,
            PostId int not null,
            UserId int not null,
            Comment text not null,
            CreatedAt timestamp default current_timestamp,
            /* FOREIGN KEY: anropar till primär nyckel (primary key) från en annan tabell. I detta fall posts och users tabellen (våra REFERENCES) 
            ON DELETE CASCADE: Raderar respektive rad från tabellen när den referade raden raderas i tabellen som anropas. 
            */
            foreign key (PostId) references posts(PostId) on delete cascade,
            foreign key (UserId) references users(UserId) on delete cascade
            )";

        $dbconn->exec($sqlComments);
        echo "Table \"Comments\" created successfully";
    } catch (PDOException $e) {
        echo $sqlComments . "<br>" . $e->getMessage();
    }

    try {
        $sqlLikes = "CREATE TABLE likes(
            LikeId int primary key auto_increment,
            PostId int not null,
            UserId int not null,
            CreatedAt timestamp default current_timestamp,
            foreign key (PostId) references posts(PostId) on delete cascade,
            foreign key (UserId) references users(UserId) on delete cascade,
            unique (PostId, UserId)
            /* Unique - ser till att PostId och UserId kolumner antar unika värden*/
            )";

        $dbconn->exec($sqlLikes);
        echo "Table \"Likes\" created successfully";
    } catch (PDOException $e) {
        echo $sqlLikes . "<br>" . $e->getMessage();
    }

    try {
        $sqlDislikes = "CREATE TABLE dislikes(
            DislikeId int primary key auto_increment,
            PostId int not null,
            UserId int not null,
            CreatedAt timestamp default current_timestamp,
            foreign key (PostId) references posts(PostId) on delete cascade,
            foreign key (UserId) references users(UserId) on delete cascade,
            unique (PostId, UserId)
            )";

        $dbconn->exec($sqlDislikes);
        echo "Table \"Likes\" created successfully";
    } catch (PDOException $e) {
        echo $sqlDislikes . "<br>" . $e->getMessage();
    }

    try {
        $sqlPasswordResetCodes = "CREATE TABLE password_reset_codes(
            CodeId int primary key auto_increment,
            UserId int not null,
            Code varchar(255) not null,
            CreatedAt timestamp default current_timestamp,
            foreign key (UserId) references users(UserId) on delete cascade
            )";

        $dbconn->exec($sqlPasswordResetCodes);
        echo "Table \"PasswordResetCodes\" created successfully";
    } catch (PDOException $e) {
        echo $sqlPasswordResetCodes . "<br>" . $e->getMessage();
    }

    $dbconn = null;
    ?>
</body>

</html>