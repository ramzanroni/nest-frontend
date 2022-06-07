<?php
   if($_POST['check']=='addtocart')
   {
       $productID=$_POST['productID'];
       $productName=$_POST['productName'];
       $productprice=$_POST['productprice'];
       $productQuantity=$_POST['productQuantity'];
       $productImage=$_POST['productImage'];
        if(isset($_COOKIE['shopping_cart']))
        {
            $cookie_data=stripcslashes($_COOKIE['shopping_cart']);
            $cartData=json_decode($cookie_data, true);
        }
        else
        {
            $cartData=array();
        }
        $item_id_list=array_column($cartData, 'productID');
        if(in_array($productID, $item_id_list))
        {
            foreach ($cartData as $key => $value) {
                if($cartData[$key]['productID']==$productID)
                {
                    $newQuantity=$cartData[$key]['productQuantity']+$productQuantity;
                    $cartData[$key]['productQuantity']=$newQuantity;
                }
            }
        }
        else
        {
            $itemArr=array(
                'productID'=>$productID,
                 'productName'=>$productName,
                 'productprice'=>$productprice,
                 'productQuantity'=>$productQuantity,
                 'productImage'=>$productImage
            );
            $cartData[]=$itemArr;
        }
      
       $itemData=json_encode($cartData);

       setcookie('shopping_cart', $itemData, time() + (86400 * 30), "/");
   }

   if($_POST['check']=='countCartItem')
   {
       $item=json_decode($_COOKIE['shopping_cart']);
       echo count($item);
       
   }


//    cartItemView

if($_POST['check']=="cartItemView")
{
        if($_COOKIE['shopping_cart']!='')
        {
            $sum=0;
            echo json_encode($_COOKIE['shopping_cart']);
        }
}

if($_POST['check']=="deleteItemFromCart")
{
    echo "working";
}
?>