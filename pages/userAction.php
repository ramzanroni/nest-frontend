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
    // print_r($response);
    if ($response->message == "success") {

        if (session_id() == '') {
            session_start();
        }
        $_SESSION['phone'] = $response->userPhone;
        $_SESSION['token'] = $response->userToken;
        // echo $response->message;
        // echo $response->userPhone;
        // echo $response->userToken;
        // echo session_id();
        echo 'success';
    } else {
        echo $response->message;
    }
}

if ($_POST['check'] == "userProfileUpdate") {
    $fullName = $_POST['fullName'];
    $emailAddress = $_POST['emailAddress'];
    $userAddress = $_POST['userAddress'];
    $userPhone = $_POST['userPhone'];
    $post = array(  //data array from user side

        "phoneNumber" => $userPhone,
        'fullName' => $fullName,
        'emailAddress' => $emailAddress,
        'userAddress' => $userAddress

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/user_profile_update.php";
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
    if ($response->message == 'success') {
        echo "success";
    } else {
        echo $response->message;
    }
}

if ($_POST['check'] == "loginpopupview") {
?>
<div class="container">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 m-auto">
            <div class="row">
                <div class="col-lg-6 pr-30 d-none d-lg-block">
                    <img class="border-radius-15" src="assets/imgs/page/login-1.png" alt="" />
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="login_wrap widget-taber-content background-white">
                        <div class="padding_eight_all bg-white" id="loginDiv">
                            <div class="heading_s1">
                                <h1 class="mb-5">Login</h1>
                                <!-- <p class="mb-30">Don't have an account? <a href="page-register.html">Create
                                                here</a></p> -->
                            </div>
                            <!-- <form method="post"> -->
                            <div class="form-group">
                                <input type="text" required="" id="phoneNumber" name="phoneNumber"
                                    placeholder="Enter your phone number*" />
                                <small class="text-danger" id="errorNumMessage"></small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-heading btn-block hover-up" name="login"
                                    onclick="userLogin()">Send</button>
                            </div>
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}