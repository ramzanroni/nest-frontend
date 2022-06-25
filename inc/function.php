<?php

function getPhone()
{
    if (isset($_COOKIE['userinfo'])) {
        $userLoginData = stripcslashes($_COOKIE['userinfo']);
        $userLoginInfo = json_decode($userLoginData, true);
        return $userLoginInfo['phone'];
    } else {
        return '';
    }
}
function getToken()
{
    if (isset($_COOKIE['userinfo'])) {
        $userLoginData = stripcslashes($_COOKIE['userinfo']);
        $userLoginInfo = json_decode($userLoginData, true);
        return $userLoginInfo['token'];
    } else {
        return '';
    }
}

function deleteUserCookie()
{
    setcookie('userinfo', "", time() - 3600, "/");
    return true;
}

define("APIKEY", "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ");
