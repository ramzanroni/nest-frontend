<?php
         //   product data fetch
    $url = 'http://192.168.0.116/neonbazar_api/product_fetch.php'; //url will be here
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( //header will be here
        'Content-Type: application/json',
        'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
    )
    );
    $productsData = curl_exec($ch);
    curl_close($ch);
    $productsArr= json_decode($productsData);

    // item with category
    $url = 'http://192.168.0.116/neonbazar_api/category_with_item.php'; //url will be here
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( //header will be here
        'Content-Type: application/json',
        'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2MTg4OTU1MjIsImp0aSI6IlRQSTVmdFFUeU5MR1ZLenFOZlVhYThyRURpdEJkRmpIS0ErUGVFMTFjMTg9IiwiaXNzIjoicHVsc2VzZXJ2aWNlc2JkLmNvbSIsImRhdGEiOnsidXNlcklkIjoiMjg4MTUiLCJ1c2VyTGV2ZWwiOjJ9fQ.wQ5AQR-fIGRZgt3CN9-W6v4PkvTIvNVP8HzCOiHHeKwcd8NT1R1Dxz_XpJH9jOa7CsDzCYBklEPRtQus11NiEQ',
    )
    );
    $categoryItem = curl_exec($ch);
    curl_close($ch);
    $categoryItemData= json_decode($categoryItem);

?>