<?php
require_once('response.php');
$response = new Response();
$response->setSuccess(false);
$response->setHttpStatusCode(200);
$response->addMessage("test message 1");
$response->addMessage("test message 2");
$response->setData('hello');
$response->send();
