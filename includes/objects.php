<?php
class User
{
    private $username;
    private $password;
    private $email;
    private $verificationCode;
    private $isVerified;

    public function __construct($username, $password, $email, $verificationCode, $isVerified)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->verificationCode = $verificationCode;
        $this->isVerified = $isVerified;
    }

    public static function retrieveUserByUsername($username, $dbconn)
    {
        try {
            $sqlUsers = "SELECT * FROM Users WHERE Username = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($username);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new User($res['Username'], $res['Password'], $res['Email'], $res['VerificationCode'], $res['IsVerified']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveUser($sqlQuery, $dbconn)
    {
        try {
            $sqlUsers = $sqlQuery;
            $stmt = $dbconn->prepare($sqlUsers);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new User($res['Username'], $res['Password'], $res['Email'], $res['VerificationCode'], $res['IsVerified']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveUserByEmail($email, $dbconn)
    {
        try {
            $sqlUsers = "SELECT * FROM Users WHERE Email = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($email);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new User($res['Username'], $res['Password'], $res['Email'], $res['VerificationCode'], $res['IsVerified']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveUserByVerificationCode($verificationCode, $dbconn)
    {
        try {
            $sqlUsers = "SELECT * FROM Users WHERE VerificationCode = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($verificationCode);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new User($res['Username'], $res['Password'], $res['Email'], $res['VerificationCode'], $res['IsVerified']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sqlUsers = "INSERT INTO Users (Username, Password, Email, VerificationCode, IsVerified, RegisteredAt) 
                VALUES (?, ?, ?, ?, ?, now())";

            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($this->username, $this->password, $this->email, $this->verificationCode, $this->isVerified);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public function verify($dbconn)
    {
        try {
            $sql = "UPDATE users SET IsVerified = '1' WHERE VerificationCode = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->verificationCode);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function updatePassword($userId, $dbconn)
    {
        try {
            $sql =  "UPDATE users SET Password = ? WHERE UserId = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->password, $userId);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function delete($dbconn)
    {
        try {
            $sql = "DELETE FROM users WHERE Username = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->username);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getRegistrationTimestamp($dbconn)
    {
        try {
            $sqlUsers = "SELECT * FROM Users WHERE Username = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($this->username);
            $stmt->execute($data);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            return $res['RegisteredAt'];
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getVerificationStatus()
    {
        return $this->isVerified;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUserId($dbconn)
    {
        try {
            $sqlUsers = "SELECT UserId FROM Users WHERE Username = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($this->username);
            $stmt->execute($data);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            return $res['UserId'];
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }
}

class Post
{
    private $userId;
    private $imageDirectoryPath;
    private $generatorPrompt;
    private $title;
    private $createdAt;

    public function __construct($userId, $imageDirectoryPath, $generatorPrompt, $title, $createdAt)
    {
        $this->userId = $userId;
        $this->imageDirectoryPath = $imageDirectoryPath;
        $this->generatorPrompt = $generatorPrompt;
        $this->title = $title;
        $this->createdAt = $createdAt;
    }

    public static function searchPosts($search, $dbconn)
    {
        try {
            $sqlUsers = "SELECT posts.*, users.Username FROM posts 
            INNER JOIN users ON posts.UserId = users.UserId
            WHERE posts.Title LIKE ? OR posts.GeneratorPrompt LIKE ? OR users.Username LIKE ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($search, $search, $search);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $posts = array();
            if ($rowCount > 0) {
                foreach ($res as $row) {
                    $post = new Post($row['UserId'], $row['ImageDirectoryPath'], $row['GeneratorPrompt'], $row['Title'], $row['CreatedAt']);
                    array_push($posts, $post);
                }
            }

            return $posts;
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public function getPostId($dbconn)
    {
        try {
            $sql = "SELECT PostId FROM Posts WHERE UserId = ? AND CreatedAt = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->userId, $this->createdAt);
            $stmt->execute($data);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            return $res['PostId'];
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO posts (UserId, ImageDirectoryPath, GeneratorPrompt, Title, CreatedAt) 
                VALUES (?, ?, ?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->userId, $this->imageDirectoryPath, $this->generatorPrompt, $this->title);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function delete($dbconn)
    {
        try {
            $sql = "DELETE FROM posts Where ImageDirectoryPath = ? AND GeneratorPrompt = ? AND Title = ? AND CreatedAt = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->imageDirectoryPath, $this->generatorPrompt, $this->title, $this->createdAt);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveAllPosts($sqlQuery, $dbconn)
    {
        try {
            $sqlUsers = $sqlQuery;
            $stmt = $dbconn->prepare($sqlUsers);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $posts = array();

                foreach ($res as $tableRow) {
                    $post = new Post(
                        $tableRow['UserId'],
                        $tableRow['ImageDirectoryPath'],
                        $tableRow['GeneratorPrompt'],
                        $tableRow['Title'],
                        $tableRow['CreatedAt']
                    );

                    array_push($posts, $post);
                }

                return $posts;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public static function retrievePost($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $posts = array();

                foreach ($res as $tableRow) {
                    $post = new Post(
                        $tableRow['UserId'],
                        $tableRow['ImageDirectoryPath'],
                        $tableRow['GeneratorPrompt'],
                        $tableRow['Title'],
                        $tableRow['CreatedAt']
                    );

                    array_push($posts, $post);
                }

                return $posts;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getImageDirectoryPath()
    {
        return $this->imageDirectoryPath;
    }

    public function getGeneratorPrompt()
    {
        return $this->generatorPrompt;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

class UserSavedImage
{
    private $userId;
    private $imageDirectoryPath;
    private $generatorPrompt;
    private $savedAt;

    public function __construct($userId, $imageDirectoryPath, $generatorPrompt, $savedAt)
    {
        $this->userId = $userId;
        $this->imageDirectoryPath = $imageDirectoryPath;
        $this->generatorPrompt = $generatorPrompt;
        $this->savedAt = $savedAt;
    }

    public static function retrieveAllSavedImagesByUser($userId, $dbconn)
    {
        try {
            $sql = "SELECT * FROM users_saved_images WHERE UserId = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($userId);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $usersSavedImages = array();

                foreach ($res as $tableRow) {
                    $savedImage = new UserSavedImage(
                        $tableRow['UserId'],
                        $tableRow['ImageDirectoryPath'],
                        $tableRow['GeneratorPrompt'],
                        $tableRow['SavedAt']
                    );

                    array_push($usersSavedImages, $savedImage);
                }

                return $usersSavedImages;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveImage($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new UserSavedImage(
                    $res['UserId'],
                    $res['ImageDirectoryPath'],
                    $res['GeneratorPrompt'],
                    $res['SavedAt']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveImageByImageDirectoryPath($userId, $imageDirectoryPath, $dbconn)
    {
        try {
            $sql = "SELECT * FROM users_saved_images WHERE UserId = ? AND ImageDirectoryPath = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($userId, $imageDirectoryPath);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new UserSavedImage(
                    $res['UserId'],
                    $res['ImageDirectoryPath'],
                    $res['GeneratorPrompt'],
                    $res['SavedAt']
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO users_saved_images (UserId, ImageDirectoryPath, GeneratorPrompt, SavedAt) 
                VALUES (?, ?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->userId, $this->imageDirectoryPath, $this->generatorPrompt);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getImageDirectoryPath()
    {
        return $this->imageDirectoryPath;
    }

    public function getGeneratorPrompt()
    {
        return $this->generatorPrompt;
    }
}

class Like
{
    private $postId;
    private $userId;
    private $CreatedAt;

    public function __construct($postId, $userId, $CreatedAt)
    {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->CreatedAt = $CreatedAt;
    }

    public static function retrieveLikeByUser($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                foreach ($res as $tableRow) {
                    $like = new Like(
                        $tableRow['PostId'],
                        $tableRow['UserId'],
                        $tableRow['CreatedAt']
                    );
                }

                return $like;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveLikes($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $likes = array();

                foreach ($res as $tableRow) {
                    $like = new Like(
                        $tableRow['PostId'],
                        $tableRow['UserId'],
                        $tableRow['CreatedAt']
                    );

                    array_push($likes, $like);
                }

                return $likes;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveRows($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();

            return $rowCount;
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO likes (PostId, UserId, CreatedAt) 
                VALUES (?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->postId, $this->userId);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function delete($dbconn)
    {
        try {
            $sql = "DELETE FROM likes WHERE PostId = ? AND UserId = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->postId, $this->userId);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCreatedAt()
    {
        return $this->CreatedAt;
    }
}

class Dislike
{
    private $postId;
    private $userId;
    private $CreatedAt;

    public function __construct($postId, $userId, $CreatedAt)
    {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->CreatedAt = $CreatedAt;
    }

    public static function retrieveDislikeByUser($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                foreach ($res as $tableRow) {
                    $dislike = new Dislike(
                        $tableRow['PostId'],
                        $tableRow['UserId'],
                        $tableRow['CreatedAt']
                    );
                }

                return $dislike;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveDislikes($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $dislikes = array();

                foreach ($res as $tableRow) {
                    $dislike = new Dislike(
                        $tableRow['PostId'],
                        $tableRow['UserId'],
                        $tableRow['CreatedAt']
                    );

                    array_push($dislikes, $dislike);
                }

                return $dislikes;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrieveRows($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();

            return $rowCount;
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO dislikes (PostId, UserId, CreatedAt) 
                VALUES (?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->postId, $this->userId);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function delete($dbconn)
    {
        try {
            $sql = "DELETE FROM dislikes WHERE PostId = ? AND UserId = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->postId, $this->userId);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCreatedAt()
    {
        return $this->CreatedAt;
    }
}

class Comment
{
    private $postId;
    private $userId;
    private $comment;
    private $createdAt;

    public function __construct($postId, $userId, $comment, $createdAt)
    {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->createdAt = $createdAt;
    }

    public static function retrieveComments($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                $comments = array();

                foreach ($res as $tableRow) {
                    $comment = new Comment(
                        $tableRow['PostId'],
                        $tableRow['UserId'],
                        $tableRow['Comment'],
                        $tableRow['CreatedAt']
                    );

                    array_push($comments, $comment);
                }

                return $comments;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO comments (PostId, UserId, Comment, CreatedAt) 
                VALUES (?, ?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->postId, $this->userId, $this->comment);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function delete($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getCommentId($dbconn)
    {
        try {
            $sqlUsers = "SELECT CommentId FROM comments WHERE UserId = ? AND PostId = ? AND Comment = ? AND CreatedAt = ?";
            $stmt = $dbconn->prepare($sqlUsers);
            $data = array($this->userId, $this->postId, $this->comment, $this->createdAt);
            $stmt->execute($data);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            return $res['CommentId'];
        } catch (PDOException $e) {
            echo $sqlUsers . "<br>" . $e->getMessage();
        }
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

class PasswordResetCode
{
    private $userId;
    private $code;
    private $createdAt;

    public function __construct($userId, $code, $CreatedAt)
    {
        $this->userId = $userId;
        $this->code = $code;
        $this->createdAt = $CreatedAt;
    }

    public static function retrievePasswordResetToken($sqlQuery, $dbconn)
    {
        try {
            $sql = $sqlQuery;
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new PasswordResetCode($res['UserId'], $res['Code'], $res['CreatedAt']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public static function retrievePasswordResetTokenByCode($code, $dbconn)
    {
        try {
            $sql = "SELECT * FROM password_reset_codes 
            WHERE Code = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($code);
            $stmt->execute($data);
            $rowCount = $stmt->rowCount();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rowCount > 0) {
                return new PasswordResetCode($res['UserId'], $res['Code'], $res['CreatedAt']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function saveToTable($dbconn)
    {
        try {
            $sql = "INSERT INTO password_reset_codes (UserId, Code, CreatedAt) 
                VALUES (?, ?, now())";

            $stmt = $dbconn->prepare($sql);
            $data = array($this->userId, $this->code);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function delete($dbconn)
    {
        try {
            $sql = "DELETE FROM password_reset_codes WHERE UserId = ? AND Code = ?";
            $stmt = $dbconn->prepare($sql);
            $data = array($this->userId, $this->code);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
