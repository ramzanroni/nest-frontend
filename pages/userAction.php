<?php
include '../inc/function.php';
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
            'Authorization: ' . APIKEY,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    // print_r($response);
    echo $response->message;
}

if ($_POST['check'] == "forRegistration") {
    $phone = $_POST['phoneNumber'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://192.168.0.116/metroapi/v1/controller/sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"newPhone\": \"$phone\"\n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 0f270d41-1e08-7365-4c29-cdfc8f1e686b"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo json_encode($response);
    }
}

if ($_POST['check'] == "userRegistration") {
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $otp = $_POST['otp'];


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://192.168.0.116/metroapi/v1/controller/sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"newUserPhone\": \"$phone\",\n\t\"name\":\"$name\",\n\t\"address\":\"$address\",\n\t\"newOtp\":\"$otp\"\n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: fb74123d-0239-d4db-a79c-3a116a503e14"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $output = json_decode($response);
        if ($output->success == true) {
            $phone = $output->data->userdata->userPhone;
            $token = $output->data->userdata->userToken;
            $info = array(
                'phone' => $phone,
                'token' => $token
            );
            $userInfo = json_encode($info);
            setcookie('userinfo', $userInfo, time() + (86400 * 30), "/");
            echo 'success';
        } else {
            echo $output->message[0];
        }
    }
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
            'Authorization: ' . APIKEY,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    if ($response->message == "success") {

        if (session_id() == '') {
            session_start();
        }
        $info = array(
            'phone' => $response->userPhone,
            'token' => $response->userToken
        );
        $userInfo = json_encode($info);

        setcookie('userinfo', $userInfo, time() + (86400 * 30), "/");


        // $_SESSION['phone'] = $response->userPhone;
        // $_SESSION['token'] = $response->userToken;
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
            'Authorization: ' . APIKEY,
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
                                    <p class="mb-30">Don't have an account? <a onclick="registrationInterface()" href="#">Create
                                            here</a></p>
                                </div>
                                <!-- <form method="post"> -->
                                <div class="form-group">
                                    <input type="text" required="" id="userName" name="userName" placeholder="Name*" />
                                    <small class="text-danger" id="errorName"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" required="" id="userAddress" name="userAddress" placeholder="Address*" />
                                    <small class="text-danger" id="errorAddress"></small>
                                </div>

                                <div class="form-group">
                                    <input type="text" required="" id="phoneNumber" name="phoneNumber" placeholder="Phone number*" />
                                    <small class="text-danger" id="errorNumMessage"></small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-heading btn-block hover-up" name="login" id="login" onclick="userLogin()">Send</button>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-heading btn-block hover-up" name="signUp" id="signUp" onclick="userSignup()">Sign Up</button>
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
if ($_POST['check'] == "userPhoneUpOTP") {
    $newPhone = $_POST['newPhone'];
    $post = array(  //data array from user side

        "phoneNumber" => $newPhone

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/change_phone_otp.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    echo $response->message;
}

if ($_POST['check'] == "checkUpdateOTP") {

    $otp = $_POST['otp'];
    $newNumber = $_POST['newNumber'];
    $oldNumber = $_POST['oldNumber'];
    $post = array(  //data array from user side

        "optNumber" => $otp,
        "phoneNew" => $newNumber,
        "phoneOld" => $oldNumber

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/change_phone_otp.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array( //header will be here
            'Content-Type: application/json',
            'Authorization: ' . APIKEY,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //output will be here
    curl_close($ch);
    $response = json_decode($server_output);
    echo $response->message;
}
?>