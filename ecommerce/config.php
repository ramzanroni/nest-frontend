<?php
require_once 'vendor/autoload.php';
$clientID = '666908999426-d5fe748fdkm77sf1e3rqf2i6feekdslg.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/nest-frontend/index.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
