<?php
if ($_POST['check'] == "placeOrder") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $area = $_POST['area'];
    $phone = $_POST['phone'];
    $town = $_POST['town'];
    $additionalPhone = $_POST['additionalPhone'];
    $additionalInfo = $_POST['additionalInfo'];
    $token = $_POST['token'];
    $paymentMethod = $_POST['paymentMethod'];
    $itemInfoArr = array();
    $cartCookiesProduct = json_decode($_COOKIE['shopping_cart']);
    foreach ($cartCookiesProduct as $cartValue) {
        $itemInfoArr[] = (object)array('productID' => $cartValue->productID, 'productQuantity' => $cartValue->productQuantity);
    }
    $itemInfo = json_encode($itemInfoArr);


    $post = array(  //data array from user side
        "name" => $name,
        "address" => $address,
        "area" => $area,
        "phone" => $phone,
        "town" => $town,
        "additionalPhone" => $additionalPhone,
        "additionalInfo" => $additionalInfo,
        "token" => $token,
        "paymentMethod" => $paymentMethod,
        "itemInfo" => $itemInfo

    );
    $data = json_encode($post); // json encoded
    $url = "http://192.168.0.116/neonbazar_api/order_action.php";
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
    if (isset($_COOKIE['shopping_cart'])) {
        setcookie('shopping_cart', "", time() - 3600, "/");
    }
    echo $response->message;
}