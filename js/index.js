$( document ).ready(function() {
    cartItem();
});

function productQuikView(productID)
{
    var check = "quickViewproduct";
    $.ajax({
        url: "pages/productView.php",
        type: "POST",
        dataType: "json",
        data: {
            productID: productID,
            check: check
        },
        success: function (response) {
            
            const obj = JSON.parse(response);
            $("#name").html(obj[0].description);
            $('#units').html(obj[0].units);
            $("#figureImage").attr("src", "//" + obj[0].img); 
            $("#sliderImg").attr("src", "//" + obj[0].img);
            $('#productPrice').html(obj[0].webprice);
        }
    });
}

// cartFunction

function addtoCart(productID, productName, productprice, productQuantity, productImage)
{
    var check = "addtocart";
    // const productInfo = { productID: productID, productName: productName, productprice: productprice, productQuantity: productQuantity, productImage: productImage };
    
    // const productJsonstr = JSON.stringify(productInfo);
    
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            productID: productID,
            productName: productName,
            productprice: productprice,
            productQuantity: productQuantity,
            productImage:productImage,
            check: check
        },
        success: function (response) {
            cartItem();
        }
    });
}

// cartItemCount
function cartItem()
{
    var check = "countCartItem";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            check: check
        },
        success: function (response) {
            $("#cartCount").html(response);
        }
    });
}

// CartPopUp

function cartPopUp()
{
    var isCardActive = document.querySelector('.active-card');
    if (!isCardActive) { 
        var active = document.getElementById('cartItem');
        active.classList.add('active-card');
    var check = "cartItemView";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
        dataType: "json",
        data: {
            check: check
        },
        success: function (response) {
            var cartData = JSON.parse(response);
            var sum = 0;
           var html = '<ul>';
            for (let i = 0; i < cartData.length; i++) {
                html += '<li><div class="shopping-cart-img"><a href="shop-product-right.html"><img alt="Nest" src="//'+cartData[i].productImage+'" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.html">Daisy Casual Bag</a></h4>   <h4><span>'+cartData[i].productQuantity+' × </span>'+cartData[i].productprice+'</h4></div><div class="shopping-cart-delete"><a onclick="deleteCatItem('+cartData[i].productID+')"><i class="fi-rs-cross-small"></i></a></div></li>';
                sum = sum + cartData[i].productprice * cartData[i].productQuantity;
            }
            html += '</ul>';
            html += '<div class="shopping-cart-footer"><div class="shopping-cart-total"><h4>Total <span>৳'+sum+'</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.html" class="outline">View cart</a><a href="shop-checkout.html">Checkout</a></div></div>';
            $("#cartItem").html(html); 
        }
    });
    }




}

// deleteItemFromCart
function deleteCatItem(id)
{
    console.log('hi');
    // var check = "deleteItemFromCart";
    // $.ajax({
    //     url: "pages/cartAction.php",
    //     type: "POST",
       
    //     data: {
    //         check: check
    //     },
    //     success: function (response) {
    //         // $("#cartItem").html(response);
    //         alert(response);
    //     }
    // });
}