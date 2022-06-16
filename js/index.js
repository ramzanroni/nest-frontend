$(document).ready(function () {
  cartItem();
});

function productQuikView(productID) {
  var check = "quickViewproduct";
  $.ajax({
    url: "pages/productView.php",
    type: "POST",
    dataType: "json",
    data: {
      productID: productID,
      check: check,
    },
    success: function (response) {
      const obj = JSON.parse(response);
      $("#name").html(obj[0].description);
      $("#productIdIn").val(obj[0].stockid);
      $("#units").html(obj[0].units);
      $("#productNameIn").val(obj[0].description);
      $("#figureImage").attr("src", "//" + obj[0].img);

      $("#sliderImg").attr("src", "//" + obj[0].img);
      $("#productImageIn").val(obj[0].img);
      $("#productPrice").html(obj[0].webprice);
      $("#productPriceIn").val(obj[0].webprice);
      $("#add-to-card-btn").removeAttr("onclick");
      $("#add-to-card-btn").attr("onClick", "addCartPopup();");
    },
  });
}

// cartFunction

function addtoCart(
  productID,
  productName,
  productprice,
  productQuantity,
  productImage
) {
  var check = "addtocart";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      productID: productID,
      productName: productName,
      productprice: productprice,
      productQuantity: productQuantity,
      productImage: productImage,
      check: check,
    },
    success: function (response) {
      cartItem();
      // $("#myTabContent").load(" #myTabContent > *");
    },
  });
}

function firstAddtoCart(
  productID,
  productName,
  productprice,
  productQuantity,
  productImage
) {
  addtoCart(
    productID,
    productName,
    productprice,
    productQuantity,
    productImage
  );
  var btnId = "#item_" + productID;
  var idbtn = "item_" + productID;
  var inc = "'increment'";
  var dec = "'decrement'";
  var newBtn =
    '<input type="hidden" id="getItem_' +
    productID +
    '" value="' +
    productQuantity +
    '"><div class="col-10 float-end after-cart"><div class="col-2 float-end increment" onclick="CartItemChange(' +
    inc +
    "," +
    productID +
    ')"<a><i class="fi-rs-plus"></i></a></div><div class="col-4 float-end middle"><a><i class="fi-rs-shopping-cart"></i><span id="cartCount_' +
    productID +
    '">' +
    productQuantity +
    '</span></a></div><div class="col-2 float-end add decrement" onclick="CartItemChange(' +
    dec +
    "," +
    productID +
    ')"><a><i class="fi-rs-minus"></i></a></div></div>';
  $(btnId).html(newBtn);
  var activediv = document.getElementById(idbtn);
  activediv.classList.add("col-12");
}

// cartItemCount
function cartItem() {
  var check = "countCartItem";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      check: check,
    },
    success: function (response) {
      $("#cartCount").html(response);
      $("#numberofCartItem").html(response);
      totalCartSum();
    },
  });
}

function totalCartSum() {
  var check = "sumCartItem";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      check: check,
    },
    success: function (response) {
      $("#total_Taka").html(response);
    },
  });
}
// CartPopUp

function cartPopUp() {
  var isCardActive = document.querySelector(".active-card");
  if (!isCardActive) {
    var active = document.getElementById("cartItem");
    active.classList.add("active-card");
    var check = "cartItemView";
    $.ajax({
      url: "pages/cartAction.php",
      type: "POST",
      dataType: "json",
      data: {
        check: check,
      },
      success: function (response) {
        var cartData = JSON.parse(response);
        // console.log(cartData);
        var sum = 0;
        var html = "<ul>";
        for (let i = 0; i < cartData.length; i++) {
          var pName = "'" + cartData[i].productName + "'";
          var pImg = "'" + cartData[i].productImage + "'";
          html +=
            '<li><div class="shopping-cart-img"><a href="shop-product-right.php?product_id=' +
            cartData[i].productID +
            '"><img alt="Nest" src="//' +
            cartData[i].productImage +
            '" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.php?product_id=' +
            cartData[i].productID +
            '">' +
            cartData[i].productName +
            "</a></h4>   <h4><span>" +
            cartData[i].productQuantity +
            " × </span>" +
            cartData[i].productprice +
            '</h4></div><div class="shopping-cart-delete"><a onclick="deleteCatItem(' +
            cartData[i].productID +
            "," +
            pName +
            "," +
            cartData[i].productprice +
            "," +
            pImg +
            "," +
            i +
            ')"><i class="fi-rs-cross-small"></i></a></div></li>';
          sum = sum + cartData[i].productprice * cartData[i].productQuantity;
        }
        html += "</ul>";
        html +=
          '<div class="shopping-cart-footer"><div class="shopping-cart-total"><h4>Total <span>৳' +
          sum +
          '</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.php" class="outline">View cart</a><a href="shop-checkout.php">Checkout</a></div></div>';
        $("#cartItem").html(html);
      },
    });
  }
}

// removeCssClass

function removeCssClass() {
  var isCardActive = document.querySelector(".active-card");
  if (isCardActive) {
    var active = document.getElementById("cartItem");
    active.classList.remove("active-card");
  }
}
// deleteItemFromCart
function deleteCatItem(
  product_id,
  product_name,
  product_price,
  product_img,
  cartIndexID
) {
  var productBtnId = "#item_" + product_id;
  var productBtnjsID = ".item_" + product_id;
  // data.parentElement.parentElement.remove();
  // console.log(item.parentElement.parentElement);
  var check = "deleteItemFromCart";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      cartIndexID: cartIndexID,
      check: check,
    },
    success: function (response) {
      cartItem();
      var cartData = JSON.parse(response);
      var sum = 0;
      var html = "<ul>";
      for (let i = 0; i < cartData.length; i++) {
        var cartPname = "'" + cartData[i].productName + "'";
        var cartPimg = "'" + cartData[i].productImage + "'";
        html +=
          '<li><div class="shopping-cart-img"><a href="shop-product-right.html"><img alt="Nest" src="//' +
          cartData[i].productImage +
          '" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.html">' +
          cartData[i].productName +
          "</a></h4>   <h4><span>" +
          cartData[i].productQuantity +
          " × </span>" +
          cartData[i].productprice +
          '</h4></div><div class="shopping-cart-delete"><a onclick="deleteCatItem(' +
          cartData[i].productID +
          "," +
          cartPname +
          "," +
          cartData[i].productprice +
          "," +
          cartPimg +
          "," +
          i +
          ')"><i class="fi-rs-cross-small"></i></a></div></li>';
        sum = sum + cartData[i].productprice * cartData[i].productQuantity;
      }
      html += "</ul>";
      html +=
        '<div class="shopping-cart-footer"><div class="shopping-cart-total"><h4>Total <span>৳' +
        sum +
        '</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.html" class="outline">View cart</a><a href="shop-checkout.php">Checkout</a></div></div>';
      $("#cartItem").html(html);
      var checkId = $(productBtnId).html();
      if (checkId != undefined) {
        var btnPname = "'" + product_name + "'";
        var btnPimg = "'" + product_img + "'";
        var btnHtml =
          '<div class="add-cart"><a class="add" onclick="firstAddtoCart(' +
          product_id +
          "," +
          btnPname +
          "," +
          product_price +
          ",1," +
          btnPimg +
          ')"><i class="fi-rs-shopping-cart mr-5"></i>Add </a></div>';
        $(productBtnId).html(btnHtml);
        $(productBtnId).removeClass("col-12");
      }
    },
  });
}
// decrement

function decrement(indexIDdown) {
  var quantityfielddown = "#quantityChnage_" + indexIDdown;
  var quantityChnageDown = $(quantityfielddown).val();
  if (quantityChnageDown > 1) {
    $(quantityfielddown).val(quantityChnageDown - 1);
  }
}
// inchrement
function inchrement(indexIDUp) {
  var quantityfieldup = "#quantityChnage_" + indexIDUp;
  var quantityChnageup = parseInt($(quantityfieldup).val());
  $(quantityfieldup).val(quantityChnageup + 1);
}
// changeQuantity
function changeQuantity(indexID, productPrice) {
  var quantityfield = "#quantityChnage_" + indexID;
  var quantityChnage = parseInt($(quantityfield).val());
  var totalPriceField = "#totalProductPrice_" + indexID;
  var currentTotalProductprice = "৳" + quantityChnage * productPrice;
  $(totalPriceField).html(currentTotalProductprice);
}

// removeItem
function removeItem(removeId) {
  var check = "deleteItemFromCart";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      cartIndexID: removeId,
      check: check,
    },
    success: function (response) {
      cartItem();
      $("#mainCartDivPage").load(" #mainCartDivPage > *");
    },
  });
}

function updateCart(totalItem) {
  var carItems = [];

  for (let i = 0; i < totalItem; i++) {
    var itemquantityField = "#quantityChnage_" + i;
    var newQuatity = $(itemquantityField).val();

    var obj = {};
    obj["index"] = i;
    obj["quantity"] = newQuatity;

    carItems.push(obj);
  }
  var cartItemJson = JSON.stringify(carItems);
  var check = "UpdateHoleCart";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      cartItemJson: cartItemJson,
      check: check,
    },
    success: function (response) {
      // console.log(response);
      $("#mainCartDivPage").load(" #mainCartDivPage > *");
      cartItem();
    },
  });
}

// cartpopupIncDec
function cartDecrement() {
  var quantityChnageDown = $("#itemQuantity").val();
  if (quantityChnageDown > 1) {
    $("#itemQuantity").val(quantityChnageDown - 1);
  }
}
// inchrement
function cartInchrement() {
  var quantityChnageup = parseInt($("#itemQuantity").val());
  $("#itemQuantity").val(quantityChnageup + 1);
}

// addCartPopup
function addCartPopup() {
  var productCartId = $("#productIdIn").val();
  var itemQuantity = $("#itemQuantity").val();
  var productNameIn = $("#productNameIn").val();
  var productPriceIn = $("#productPriceIn").val();
  var productImageIn = $("#productImageIn").val();
  addtoCart(
    productCartId,
    productNameIn,
    productPriceIn,
    itemQuantity,
    productImageIn
  );
  cartItem();
}

function CartItemChange(
  statuspre,
  cartItemID,
  itemDescription,
  itemPrice,
  itemImg
) {
  var cartItemField = "#getItem_" + cartItemID;
  var cartCounterId = "#cartCount_" + cartItemID;
  var newproductBtn = "#item_" + cartItemID;
  var cartItemNum = $(cartItemField).val();
  var newItem = "";
  var status = statuspre.trim();
  if (status == "decrement") {
    // if (cartItemNum > 1) {
    newItem = cartItemNum - 1;
    // }
  }
  if (status == "increment") {
    newItem = parseInt(cartItemNum) + 1;
  }
  var check = "cartItemUpdateData";
  // if (newItem > 0) {
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      newItem: newItem,
      cartItemID: cartItemID,
      check: check,
    },
    success: function (response) {
      if (newItem != 0) {
        $(cartItemField).val(newItem);
        $(cartCounterId).html(newItem);
      } else {
        var btnPnamenew = "'" + itemDescription + "'";
        var btnPimgnew = "'" + itemImg + "'";
        var newbtnHtml =
          '<div class="add-cart"><a class="add" onclick="firstAddtoCart(' +
          cartItemID +
          "," +
          btnPnamenew +
          "," +
          itemPrice +
          ",1," +
          btnPimgnew +
          ')"><i class="fi-rs-shopping-cart mr-5"></i>Add </a></div>';
        $(newproductBtn).html(newbtnHtml);
        $(newproductBtn).removeClass("col-12");
      }
      cartItem();
    },
  });
  // }
}

function cartInchrementSingle() {
  var quantityChnageupSingle = parseInt($("#itemQuantitySingle").val());
  $("#itemQuantitySingle").val(quantityChnageupSingle + 1);
}

function cartDecrementSingle() {
  var quantityChnageDownSingle = $("#itemQuantitySingle").val();
  if (quantityChnageDownSingle > 1) {
    $("#itemQuantitySingle").val(quantityChnageDownSingle - 1);
  }
}

// addtoCartSingle

function addtoCartSingle(productID, productName, productPrice, productImage) {
  var productQuantity = $("#itemQuantitySingle").val();
  addtoCart(
    productID,
    productName,
    productPrice,
    productQuantity,
    productImage
  );
}
$(document).ready(function () {
  $("#limitValue li").on("click", function () {
    var limit = $(this).attr("data-id");
    $("#getLimit").val(limit);

    sortingProduct();
  });
  $("#sortByValue li").on("click", function () {
    var sortBy = $(this).attr("data-id");
    $("#getSortByValue").val(sortBy);
    sortingProduct();
  });
});

function sortingProduct() {
  var limitValueData = $("#getLimit").val();
  var sortByData = $("#getSortByValue").val();
  var catID = $("#catId").val();
  var check = "sortingProductList";
  $.ajax({
    url: "pages/productPageAction.php",
    type: "POST",

    data: {
      limitValueData: limitValueData,
      sortByData: sortByData,
      catID: catID,
      check: check,
    },
    success: function (response) {
      $("#productItem").html(response);
      $(".sort-by-cover").removeClass("show");
      $(".sort-by-dropdown").removeClass("show");
      // $("#mainCartDivPage").load(" #mainCartDivPage > *");
    },
  });
}

// pagination

function pagination(pageNumber) {
  var limitValueData = $("#getLimit").val();
  var sortByData = $("#getSortByValue").val();
  var catID = $("#catId").val();
  var check = "paginationProduct";
  var pageID = "#pagination_" + pageNumber;
  $.ajax({
    url: "pages/productPageAction.php",
    type: "POST",

    data: {
      limitValueData: limitValueData,
      sortByData: sortByData,
      catID: catID,
      pageNumber: pageNumber,
      check: check,
    },
    success: function (response) {
      $("#productItem").html(response);
    },
  });
}

// categoryProduct
function categoryProduct(categoryId) {
  var check = "categoryWiseProduct";
  $.ajax({
    url: "pages/productPageAction.php",
    type: "POST",

    data: {
      categoryId: categoryId,
      check: check,
    },
    success: function (response) {
      $("#productItemField").html(response);
    },
  });
}

// userLogin
function timmer() {
  var fiveMinutes = 60 * 1,
      display = document.querySelector('#time');
  startTimer(fiveMinutes, display);
};

function startTimer(duration, display) {
  var timer = duration, minutes, seconds;
  setInterval(function () {
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      display.textContent = minutes + ":" + seconds;

      if (--timer < 0) {
          timer = duration;
      }
  }, 1000);
}
function userLogin() {
  var phoneNumber = $("#phoneNumber").val();
  if ($.isNumeric(phoneNumber)) {
    var phoneReg = new RegExp(/(^(\+88|0088)?(01){1}[56789]{1}(\d){8})$/);
    if (!phoneReg.test(phoneNumber)) {
      $("#errorNumMessage").html("Please Enter a Valid Number");
    } else {
      $("#errorNumMessage").html("");
      var check = "userPhoneNumberSend";
      $.ajax({
        url: "pages/userAction.php",
        type: "POST",

        data: {
          phoneNumber:phoneNumber,
          check: check,
        },
        success: function (response) {
          console.log(response);
          if (response == "success")
          {
            var phone = "'"+phoneNumber+"'";
            var htmlgetOtp='<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="getOtp" name="getOtp"              placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkOTP('+phone+')">Log in</button></div><a class="btn btn-primary" id="resendField" onclick="resendOTP('+phone+')">Resend OTP</a><p id="countDown">Resend OTP after: <span id="time"></span>';
            $("#loginDiv").html(htmlgetOtp);
            $("#resendField").hide();
            timmer();
            setTimeout(function () { 
              $("#countDown").hide();
              $("#resendField").show();
          }, 60000);
          }
          else {
            $("#errorNumMessage").html(response);
          }
          

        },
      });
    }
  } else {
    $("#errorNumMessage").html("Please Enter a Valid Number");
  }
}




function resendOTP(phoneNumber)
{
  var check = "userPhoneNumberSend";
      $.ajax({
        url: "pages/userAction.php",
        type: "POST",

        data: {
          phoneNumber:phoneNumber,
          check: check,
        },
        success: function (response) {
          if (response != "success")
          {
            var phone = "'"+phoneNumber+"'";
            var htmlLogin =' <div class="heading_s1"><h1 class="mb-5">Login</h1> </div><div class="form-group"><input type="text" required="" id="phoneNumber" name="phoneNumber" placeholder="Enter your phone number*" /><small class="text-danger" id="errorNumMessage">'+response+'</small></div><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="userLogin()">Send</button></div>'
          $("#loginDiv").html(htmlLogin);
          }
          

        },
      });
}


// checkOTP
function checkOTP(phone)
{
  var otpCode = $("#getOtp").val();
  var check = "otpCheck";
  if (otpCode != '') {
    $.ajax({
      url: "pages/userAction.php",
      type: "POST",

      data: {
        otpCode: otpCode,
        phone: phone,
        check: check,
      },
      success: function (response) {
        console.log(response);
        if (response == "success")
        {
          // window.location.href = "page-account.php";
          location.reload();
        }
        else {
          $("#errorNumMessage").html(response);
        }
        
        // $("#productItemField").html(response);
      },
    });
  } else {
    $("#errorNumMessage").html("Please enter OTP number.");
  }
}

// updateUserData

function updateUserData(userPhone)
{
  var fullName = $("#fullName").val();
  var emailAddress = $("#emailAddress").val();
  var userAddress = $("#userAddress").val();
  var flag = 1;
  var check = "userProfileUpdate";
  if (fullName == "") {
    $("#fullName").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (emailAddress == "") {
    $("#emailAddress").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (userAddress == "") {
    $("#userAddress").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (flag == 1)
  {
    
    $.ajax({
      url: "pages/userAction.php",
      type: "POST",
  
      data: {
        fullName: fullName,
        emailAddress: emailAddress,
        userAddress: userAddress,
        userPhone:userPhone,
        check: check,
      },
      success: function (response) {
        if (response == "success")
        {
          $("#accountDiv").load(" #accountDiv > *");
        }
        else {
          $("#accountError").html(response);
        }
      },
    });
  }
}

// loginUserFororder
function loginUserFororder()
{
  var check="loginpopupview"
  $.ajax({
    url: "pages/userAction.php",
    type: "POST",

    data: {
      check: check
    },
    success: function (response) {
      $("#modalDiv").html(response);
    },
  });
 
}

// placeorder
function placeorder(phoneNumber, token)
{
  var paymentMethod = $("input[name='payment_option']:checked").val();
  var name = $("#name").val();
  var address = $("#address").val();
  var area = $("#area").val();
  var phone = $("#phone").val();
  var town = $("#town").val();
  var additionalPhone = $("#additionalPhone").val();
  var additionalInfo = $("#additionalInfo").val();
  var flag = 1;
  var check = 'placeOrder';
  if (name == "") {
    $("#name").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (address == "") {
    $("#address").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (area == "") {
    $("#area").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (phone == "") {
    $("#phone").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (town == "") {
    $("#town").css({ "border": "1px solid red" });
    flag = 0;
  }
  if (flag == 1) {
    $.ajax({
      url: "pages/orderAction.php",
      type: "POST",

      data: {
        name: name,
        address: address,
        area: area,
        phone: phone,
        town: town,
        additionalPhone: additionalPhone,
        additionalInfo: additionalInfo,
        paymentMethod: paymentMethod,
        token: token,
        check: check
      },
      success: function (response) {
        console.log(response);
        if (response == "success") {
        cartItem();
        window.location.replace("page-account.php");
          
        }
      },
    });
  }

}