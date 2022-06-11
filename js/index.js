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
            $("#productIdIn").val(obj[0].stockid);
            $('#units').html(obj[0].units);
            $('#productNameIn').val(obj[0].description);
            $("#figureImage").attr("src", "//" + obj[0].img); 

            $("#sliderImg").attr("src", "//" + obj[0].img);
            $("#productImageIn").val(obj[0].img);
            $('#productPrice').html(obj[0].webprice); 
            $('#productPriceIn').val(obj[0].webprice);
            $('#add-to-card-btn').removeAttr('onclick');
            $('#add-to-card-btn').attr('onClick', 'addCartPopup();');
        }
    });
}

// cartFunction

function addtoCart(productID, productName, productprice, productQuantity, productImage)
{
    var check = "addtocart";
    
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
            $("#myTabContent").load(" #myTabContent > *");
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
            $("#numberofCartItem").html(response);
            totalCartSum();
        }
    });
}

function totalCartSum()
{
    var check = "sumCartItem";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            check: check
        },
        success: function (response) {
            $("#total_Taka").html(response);           
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
                html += '<li><div class="shopping-cart-img"><a href="shop-product-right.php?product_id='+cartData[i].productID+'"><img alt="Nest" src="//'+cartData[i].productImage+'" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.php?product_id='+cartData[i].productID+'">'+cartData[i].productName+'</a></h4>   <h4><span>'+cartData[i].productQuantity+' × </span>'+cartData[i].productprice+'</h4></div><div class="shopping-cart-delete"><a onclick="deleteCatItem(this,'+i+')"><i class="fi-rs-cross-small"></i></a></div></li>';
                sum = sum + cartData[i].productprice * cartData[i].productQuantity;
            }
            html += '</ul>';
            html += '<div class="shopping-cart-footer"><div class="shopping-cart-total"><h4>Total <span>৳'+sum+'</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.html" class="outline">View cart</a><a href="shop-checkout.php">Checkout</a></div></div>';
            $("#cartItem").html(html); 
        }
    });
    }

}

// removeCssClass

function removeCssClass()
{
    var isCardActive = document.querySelector('.active-card');
    if (isCardActive)
    {
        var active = document.getElementById('cartItem');
        active.classList.remove('active-card');
        }
}
// deleteItemFromCart
function deleteCatItem(data,cartIndexID)
{
    // var item = data;
    // data.parentElement.parentElement.remove();
    // console.log(item.parentElement.parentElement);
    var check = "deleteItemFromCart";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            cartIndexID:cartIndexID,
            check: check
        },
        success: function (response) {
            cartItem();
            var cartData = JSON.parse(response);
            var sum = 0;
           var html = '<ul>';
            for (let i = 0; i < cartData.length; i++) {
                html += '<li><div class="shopping-cart-img"><a href="shop-product-right.html"><img alt="Nest" src="//'+cartData[i].productImage+'" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.html">'+cartData[i].productName+'</a></h4>   <h4><span>'+cartData[i].productQuantity+' × </span>'+cartData[i].productprice+'</h4></div><div class="shopping-cart-delete"><a onclick="deleteCatItem(this,'+i+')"><i class="fi-rs-cross-small"></i></a></div></li>';
                sum = sum + cartData[i].productprice * cartData[i].productQuantity;
            }
            html += '</ul>';
            html += '<div class="shopping-cart-footer"><div class="shopping-cart-total"><h4>Total <span>৳'+sum+'</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.html" class="outline">View cart</a><a href="shop-checkout.php">Checkout</a></div></div>';
            $("#cartItem").html(html); 
            $("#myTabContent").load(" #myTabContent > *");
            
            
        }
    });
}
// decrement

function decrement(indexIDdown)
{
    var quantityfielddown = "#quantityChnage_" + indexIDdown;
    var quantityChnageDown = $(quantityfielddown).val();
    if (quantityChnageDown > 1)
    {
        $(quantityfielddown).val(quantityChnageDown - 1);
        }
}
// inchrement
function inchrement(indexIDUp)
{
    var quantityfieldup = "#quantityChnage_" + indexIDUp;
    var quantityChnageup = parseInt($(quantityfieldup).val());
        $(quantityfieldup).val(quantityChnageup + 1);
}
// changeQuantity
function changeQuantity(indexID, productPrice)
{
    var quantityfield = "#quantityChnage_" + indexID;
    var quantityChnage = parseInt($(quantityfield).val());
    var totalPriceField = "#totalProductPrice_" + indexID;
    var currentTotalProductprice = "৳"+(quantityChnage * productPrice);
    $(totalPriceField).html(currentTotalProductprice);
}

// removeItem
function removeItem(removeId)
{
    var check = "deleteItemFromCart";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            cartIndexID:removeId,
            check: check
        },
        success: function (response) {
            cartItem();
            $("#mainCartDivPage").load(" #mainCartDivPage > *");
            
        }
    });
}

function updateCart(totalItem)
{
    var carItems = [];

    for (let i = 0; i < totalItem; i++) {
        var itemquantityField = "#quantityChnage_" + i;
        var newQuatity = $(itemquantityField).val();

        var obj = {};
        obj['index'] = i;
        obj['quantity'] = newQuatity;

        carItems.push(obj);
    }
    var cartItemJson = JSON.stringify(carItems);
    var check = "UpdateHoleCart";
    $.ajax({
        url: "pages/cartAction.php",
        type: "POST",
       
        data: {
            cartItemJson:cartItemJson,
            check: check
        },
        success: function (response) {
            // console.log(response);
            $("#mainCartDivPage").load(" #mainCartDivPage > *");
            cartItem();
        }
    });
}

// cartpopupIncDec
function cartDecrement()
{
    
    var quantityChnageDown = $("#itemQuantity").val();
    if (quantityChnageDown > 1)
    {
        $("#itemQuantity").val(quantityChnageDown - 1);
        }
}
// inchrement
function cartInchrement()
{

    var quantityChnageup = parseInt($("#itemQuantity").val());
        $("#itemQuantity").val(quantityChnageup + 1);
}

// addCartPopup
function addCartPopup()
{
    var productCartId = $("#productIdIn").val();
    var itemQuantity = $("#itemQuantity").val();
    var productNameIn = $("#productNameIn").val();
    var productPriceIn = $("#productPriceIn").val();
    var productImageIn = $("#productImageIn").val();
    addtoCart(productCartId, productNameIn, productPriceIn, itemQuantity, productImageIn);
    cartItem();

}

function CartItemChange(status, cartItemID)
{

    var cartItemField = "#getItem_" + cartItemID;
    var cartCounterId = "#cartCount_" + cartItemID;
    var cartItemNum = $(cartItemField).val();
    var newItem = '';
    if (status == "decrement")
    {
        if (cartItemNum > 1)
        {
            newItem = cartItemNum - 1;
        }
    }
    if (status == "increment")
    {
        newItem =parseInt(cartItemNum) + 1;
    }
    var check = "cartItemUpdateData";
    if (newItem > 0)
    {
        $.ajax({
            url: "pages/cartAction.php",
            type: "POST",
           
            data: {
                newItem: newItem,
                cartItemID:cartItemID,
                check: check
            },
            success: function (response) {
                $(cartItemField).val(newItem);
                $(cartCounterId).html(newItem);
                cartItem();
                // $("#mainCartDivPage").load(" #mainCartDivPage > *");
            }
        });
        }
    
}

function cartInchrementSingle()
{
    
    var quantityChnageupSingle = parseInt($("#itemQuantitySingle").val());
        $("#itemQuantitySingle").val(quantityChnageupSingle + 1);
}

function cartDecrementSingle()
{
    var quantityChnageDownSingle = $("#itemQuantitySingle").val();
    if (quantityChnageDownSingle > 1)
    {
        $("#itemQuantitySingle").val(quantityChnageDownSingle - 1);
        }
}

// addtoCartSingle

function addtoCartSingle(productID, productName, productPrice, productImage)
{
    var productQuantity = $("#itemQuantitySingle").val();
    addtoCart(productID, productName, productPrice, productQuantity, productImage);
    
}