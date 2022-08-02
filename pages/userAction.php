<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
if ($_POST['check'] == "userPhoneNumberSend") {
    $phoneNumber = trim($_POST['phoneNumber']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"phone\": \"$phoneNumber\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        if ($result->success == true) {
            echo "success";
        } else {
            echo $result->message[0];
        }
    }
}

if ($_POST['check'] == "checkEmail") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $emailID = trim($_POST['emailID']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"login_id\":\"$emailID\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
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
            $phone = $output->data->userdata[0]->userPhone;
            $token = $output->data->userdata[0]->userToken;
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

if ($_POST['check'] == "forRegistration") {
    $phone = trim($_POST['phoneNumber']);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"newPhone\": \"$phone\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
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
    $phone = trim($_POST['phone']);
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $otp = trim($_POST['otp']);
    $emailAdd = trim($_POST['emailAdd']);
    $emailIdData = trim($_POST['emailIdData']);
    $mediaData = trim($_POST['mediaData']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"newUserPhone\": \"$phone\",\n\t\"address\": \"$address\",\n\t\"name\":\"$name\",\n\t\"newOtp\":\"$otp\",\n\t\"email\":\"$emailAdd\",\n\t\"login_media\":\"$mediaData\",\n\t\"login_id\": \"$emailIdData\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
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
    $otpCode = trim($_POST['otpCode']);
    $phone = trim($_POST['phone']);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "sms.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"phone\": \"$phone\",\n\t\"otp\": \"$otpCode\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        if ($result->success == true) {
            $info = array(
                'phone' => $result->data->userdata[0]->userPhone,
                'token' => $result->data->userdata[0]->userToken
            );
            $userInfo = json_encode($info);

            setcookie('userinfo', $userInfo, time() + (86400 * 30), "/");
            echo 'success';
        } else {
            echo $result->message[0];
        }
    }
}

if ($_POST['check'] == "userProfileUpdate") {
    $fullName = trim($_POST['fullName']);
    $emailAddress = trim($_POST['emailAddress']);
    $userAddress = trim($_POST['userAddress']);
    $userPhone = trim($_POST['userPhone']);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "user.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => "\n\t{\n\t\"phoneNumber\":\"$userPhone\",\n\t\"fullName\": \"$fullName\",\n\t\"emailAddress\":\"$emailAddress\",\n\t\"userAddress\": \"$userAddress\"\n}\n",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        if ($result->success == true) {
            echo "success";
        } else {
            echo $result->message[0];
        }
    }
}

if ($_POST['check'] == "loginpopupview") {
    include '../config.php';
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
                                    <p class="mb-30">Don't have an account? <a id="regInterface" onclick="registrationInterface()" href="#">Create
                                            here</a> <a href="#" onclick="loingInterface()" id="loginView">Login</a></p>
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
                                <input type="hidden" name="email" id="email" value="">
                                <input type="hidden" name="emailID" id="emailID" value="">
                                <input type="hidden" name="media" id="media" value="1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-heading btn-block hover-up" name="login" id="login" onclick="userLogin()">Send</button>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-heading btn-block hover-up" name="signUp" id="signUp" onclick="userSignup()">Sign Up</button>
                                </div>
                                <!-- </form> -->
                                <?php
                                echo "<a id='gmailLogin' href='" . $client->createAuthUrl() . "'><img src='assets/imgs/login-google.png'></a>";
                                ?>
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
    $newPhone = trim($_POST['newPhone']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "changeOtp.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"phoneNumber\":\"$newPhone\"\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        if ($result->success == true) {
            echo "success";
        } else {
            echo $result->message[0];
        }
    }
}

if ($_POST['check'] == "checkUpdateOTP") {

    $otp = $_POST['otp'];
    $newNumber = trim($_POST['newNumber']);
    $oldNumber = trim($_POST['oldNumber']);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "changeOtp.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n\t\"optNumber\":\" $otp\",\n\t\"phoneNew\": \"$newNumber\",\n\t\"phoneOld\":\"$oldNumber\"\n\t\n}",
        CURLOPT_HTTPHEADER => array(
            "Authorization:" . APIKEY,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $result = json_decode($response);
        if ($result->success == true) {
            echo "success";
        } else {
            echo $result->message[0];
        }
    }
}
?>