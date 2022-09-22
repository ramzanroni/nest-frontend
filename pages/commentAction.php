<?php
include '../inc/function.php';
include '../inc/apiendpoint.php';
if ($_POST['check'] == "addComment") {
    $comment = $_POST['comment'];
    $commentId = $_POST['commentId'];
    $userPhone = $_POST['userPhone'];
    $commentInfo = array(  //data array from user side
        "blog_id" => $commentId,
        "comment" => $comment,
        "ratings" => 5,
        "phone" => $userPhone
    );




    $data = json_encode($commentInfo); // json encoded


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => APIENDPOINT . "admin/blog/blog-comment.php?blog_id=" . $commentId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
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
            echo 'success';
        } else {
            echo "Something in wrong.";
        }
    }
}
