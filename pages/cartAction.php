<?php
include '../inc/function.php';
if ($_POST['check'] == 'addtocart') {
    $productID = trim($_POST['productID']);
    echo $productName = $_POST['productName'];
    $productprice = trim($_POST['productprice']);
    $productQuantity = trim($_POST['productQuantity']);
    $productImage = trim($_POST['productImage']);
    if (isset($_COOKIE['shopping_cart'])) {
        $cookie_data = $_COOKIE['shopping_cart'];
        $cartData = json_decode($cookie_data, true);
    } else {
        $cartData = array();
    }
    $item_id_list = array_column($cartData, 'productID');
    if (in_array($productID, $item_id_list)) {
        foreach ($cartData as $key => $value) {
            if ($cartData[$key]['productID'] == $productID) {
                $newQuantity = $cartData[$key]['productQuantity'] + $productQuantity;
                $cartData[$key]['productQuantity'] = $newQuantity;
            }
        }
    } else {
        $itemArr = array(
            'productID' => $productID,
            'productName' => $productName,
            'productprice' => $productprice,
            'productQuantity' => $productQuantity,
            'productImage' => $productImage
        );
        $cartData[] = $itemArr;
    }
    print_r($cartData);
    $itemData = json_encode($cartData);

    setcookie('shopping_cart', $itemData, time() + (86400 * 30), "/");
}

if ($_POST['check'] == 'countCartItem') {
    $item = json_decode($_COOKIE['shopping_cart']);
    echo count($item);
}


//    cartItemView

if ($_POST['check'] == "cartItemView") {
    if ($_COOKIE['shopping_cart'] != '') {
        echo json_encode($_COOKIE['shopping_cart']);
    }
}

if ($_POST['check'] == "deleteItemFromCart") {
    $cartIndexID = trim($_POST['cartIndexID']);
    if ($_COOKIE['shopping_cart'] != '') {
        $cookie_data = $_COOKIE['shopping_cart'];
        $cartData = json_decode($cookie_data, true);
        unset($cartData[$cartIndexID]);
        $nowCartData = array_values($cartData);
        $afterRemoveCart = json_encode($nowCartData);
        setcookie('shopping_cart', $afterRemoveCart, time() + (86400 * 30), "/");
        echo $afterRemoveCart;
    }
}
if ($_POST['check'] == 'clearCart') {
    setcookie('shopping_cart', '', time() + (86400 * 30), "/");
}
// UpdateHoleCart

if ($_POST['check'] == 'UpdateHoleCart') {
    $cartItemJson = json_decode($_POST['cartItemJson']);
    $item = count($cartItemJson);
    $cartItem = json_decode($_COOKIE['shopping_cart']);
    // print_r($cartItem);
    for ($i = 0; $i < $item; $i++) {
        //   echo $cartItemJson[]->$i;
        if ($cartItemJson[$i]->quantity != $cartItem[$i]->productQuantity) {
            $cartItem[$i]->productQuantity = $cartItemJson[$i]->quantity;
        }
    }
    $afterUpdateCart = json_encode($cartItem);
    setcookie('shopping_cart', $afterUpdateCart, time() + (86400 * 30), "/");
}

if ($_POST['check'] == 'cartItemUpdateData') {
    $newItem = trim($_POST['newItem']);
    $cartItemID = trim($_POST['cartItemID']);
    if ($newItem == 0) {
        if ($_COOKIE['shopping_cart'] != '') {
            $cookiesIndex = '';
            $cookie_data = $_COOKIE['shopping_cart'];
            $cartData = json_decode($cookie_data, true);
            $dataItem = count($cartData);
            for ($i = 0; $i < $dataItem; $i++) {
                if ($cartData[$i]['productID'] == $cartItemID) {
                    $cookiesIndex = $i;
                }
            }
            unset($cartData[$cookiesIndex]);
            $nowCartData = array_values($cartData);
            $afterRemoveCart = json_encode($nowCartData);
            setcookie('shopping_cart', $afterRemoveCart, time() + (86400 * 30), "/");
        }
    } else {
        $cartArray = json_decode($_COOKIE['shopping_cart']);
        $cartCount = count($cartArray);
        for ($i = 0; $i < $cartCount; $i++) {
            if ($cartArray[$i]->productID == $cartItemID) {
                $cartArray[$i]->productQuantity = $newItem;
            }
        }
        $newCart = json_encode($cartArray);
        setcookie('shopping_cart', $newCart, time() + (86400 * 30), "/");
    }
}

if ($_POST['check'] == "sumCartItem") {
    if ($_COOKIE['shopping_cart'] != '') {
        $cookiesItem = json_decode($_COOKIE['shopping_cart']);
        $sum = 0;
        foreach ($cookiesItem as $key => $Cartvalue) {
            $sum = $sum + $Cartvalue->productprice * $Cartvalue->productQuantity;
        }
        echo $sum;
    } else {
        echo 0;
    }
}
