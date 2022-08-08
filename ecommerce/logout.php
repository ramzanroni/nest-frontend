<?php
include 'inc/function.php';

$destroyCookie = deleteUserCookie();
if ($destroyCookie == true) {
    header("Location: index.php");
}
