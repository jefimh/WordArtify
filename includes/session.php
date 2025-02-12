<?php
session_start();

if (!isset($_SESSION['sessionId'])) {
    header('Location: login.php');
}
?>