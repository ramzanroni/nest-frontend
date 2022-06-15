<?php

if ($_POST['check'] == "userPhoneNumberSend") {
    $phoneNumber = $_POST['phoneNumber'];
    $post = array(  //data array from user side

        "phoneNumber" => $phoneNumber

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/user_send_otp.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    echo $response->message;
}

if ($_POST['check'] == "otpCheck") {
    $otpCode = $_POST['otpCode'];
    $phone = $_POST['phone'];
    $post = array(  //data array from user side

        "optNumber" => $otpCode,
        'phone' => $phone

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/user_send_otp.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    if ($response->message = "success") {

        if (session_id() == '') {
            session_start();
        }
        $_SESSION['phone'] = $response->userPhone;
        $_SESSION['token'] = $response->userToken;
        echo $response->message;
        echo $response->userPhone;
        echo $response->userToken;
        echo session_id();
    }
}