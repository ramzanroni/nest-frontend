<?php
session_start();
// session_destroy();
unset($_SESSION['phone'], $_SESSION['token']);
if (!isset($_SESSION['phone']) && !isset($_SESSION['token'])) {
    header("Location: index.php");
} else {
    echo "Session don't destroy..!";
}