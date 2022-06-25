$(window).on("orientationchange", function (event) {
  cartItem();
  cartPopUp();
});
var defultCurrentSize = screen.width;
$(window).resize(function () {
  var currentSize = screen.width;
  // console.log("current" + currentSize);
  if (currentSize < 991 && defultCurrentSize < 991) {
    // console.log("no need small");
  } else if (currentSize > 991 && defultCurrentSize > 991) {
    // console.log("no need big");
  } else {
    cartItem();
    cartPopUp();
    defultCurrentSize = currentSize;
    console.log("need change");
  }

  //   if (screen.width < 991) {
  //     console.log('<991');

  //     $("#web-sticky").removeClass('sticky-bar');
  //     $("#mobile-sticky").addClass('sticky-bar');
  // }
  // if (screen.width > 991) {
  //     console.log('>991');
  //     $("#web-sticky").addClass('sticky-bar');
  //     $("#mobile-sticky").removeClass('sticky-bar');
  // }
});

function alertMessage(message) {
  $(".alert").addClass("show");
  $(".alert").removeClass("hide");
  $(".alert").addClass("showAlert");
  $("#errorMessage").html(message);
  setTimeout(function () {
    $(".alert").css({ display: "block !important" });
    $(".alert").removeClass("show");
    $(".alert").addClass("hide");
  }, 2000);
}

function alertMessageSuccess(message) {
  $(".alertSuccess").addClass("show");
  $(".alertSuccess").removeClass("hide");
  $(".alertSuccess").addClass("showAlert");
  $("#errorMessageSuccess").html(message);
  setTimeout(function () {
    $(".alertSuccess").css({ display: "block !important" });
    $(".alertSuccess").removeClass("show");
    $(".alertSuccess").addClass("hide");
  }, 2000);
}

function preloader() {
  $("#preloader-active").delay(100).fadeOut("slow");
  $("body").delay(100).css({
    overflow: "visible",
  });
  $("#onloadModal").modal("show");
}

$(".close-btn").click(function () {
  $(".alertSuccess").removeClass("show");
  $(".alertSuccess").addClass("hide");
});

$(document).ready(function () {
  cartItem();
  $("#searchBox").hide();
  $("#searchResultBox").hide();

  // $("#preloader-active").hide();
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
  alertMessageSuccess("Product Cart Success..");
}

// cartItemCount
function cartItem() {
  var mobile = screen.width < 990 ? "Mobile" : "";
  var check = "countCartItem";
  $.ajax({
    url: "pages/cartAction.php",
    type: "POST",

    data: {
      check: check,
    },
    success: function (response) {
      // console.log($("#cartCount"+mobile));
      $("#cartCount" + mobile).html(response);
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
  var mobile = screen.width < 991 ? "Mobile" : "";
  var isCardActive = document.querySelector(".active-card");
  if (!isCardActive) {
    var active = document.getElementById("cartItem" + mobile);
    active.classList.add("active-card");
    var check = "cartItemView";
    // console.log("working");

    $.ajax({
      url: "pages/cartAction.php",
      type: "POST",
      dataType: "json",
      data: {
        check: check,
      },
      success: function (response) {
        var cartData = JSON.parse(response);
        var html = "No product in your cart.";
        if (cartData.length > 0) {
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
              cartData[i].productName.substring(0, 15) +
              "...</a></h4>   <h4><span>" +
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
        } 
        $("#cartItem" + mobile).html(html);
      },
    });
  }
}

// removeCssClass

function removeCssClass() {
  var mobile = screen.width < 991 ? "Mobile" : "";

  var isCardActive = document.querySelector(".active-card");
  if (isCardActive) {
    var active = document.getElementById("cartItem" + mobile);
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
  var mobile = screen.width < 990 ? "Mobile" : "";

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
      alertMessageSuccess("Cart Product Delete Success..");
      var cartData = JSON.parse(response);
      var sum = 0;
      var html = "<ul>";
      for (let i = 0; i < cartData.length; i++) {
        var cartPname = "'" + cartData[i].productName + "'";
        var cartPimg = "'" + cartData[i].productImage + "'";
        html +=
          '<li><div class="shopping-cart-img"><a href="shop-product-right.php"><img alt="Nest" src="//' +
          cartData[i].productImage +
          '" /></a></div><div class="shopping-cart-title"><h4><a href="shop-product-right.php">' +
          cartData[i].productName.substring(0, 15) +
          "...</a></h4>   <h4><span>" +
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
        '</span></h4></div><div class="shopping-cart-button"><a href="shop-cart.php" class="outline">View cart</a><a href="shop-checkout.php">Checkout</a></div></div>';
      $("#cartItem" + mobile).html(html);
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
      alertMessageSuccess("Remove Cart Success..");
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
      alertMessageSuccess("Update Cart Success..");
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
      alertMessageSuccess("Save Success..");
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
  alertMessageSuccess("Save Success..");
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
    beforeSend: function () {
      $("#preloader-active").show();
    },
    success: function (response) {
      $("#preloader-active").hide();
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
    beforeSend: function () {
      $("#preloader-active").show();
    },

    success: function (response) {
      $("#preloader-active").hide();
      $("#productItemField").html(response);
    },
  });
}

// userLogin
function timmer() {
  var fiveMinutes = 60 * 1,
    display = document.querySelector("#time");
  startTimer(fiveMinutes, display);
}

function startTimer(duration, display) {
  var timer = duration,
    minutes,
    seconds;
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
      console.log(phoneNumber);
      $.ajax({
        url: "pages/userAction.php",
        type: "POST",

        data: {
          phoneNumber: phoneNumber,
          check: check,
        },
        success: function (response) {
          console.log(response);
          if (response == "success") {
            var phone = "'" + phoneNumber + "'";
            var htmlgetOtp =
              '<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="getOtp" name="getOtp" placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><p id="countDown">OTP has been send! <span id="time"></span></p><a class="" id="resendField" onclick="resendOTP('+phone+')">Resend OTP</a><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkOTP('+phone+')">Log in</button></div>';
            $("#loginDiv").html(htmlgetOtp);
            $("#resendField").hide();
            timmer();
            setTimeout(function () {
              $("#countDown").hide();
              $("#resendField").show();
            }, 60000);
          } else {
            $("#errorNumMessage").html(response);
          }
        },
      });
    }
  } else {
    $("#errorNumMessage").html("Please Enter a Valid Number");
  }
}

function resendOTP(phoneNumber) {
  var check = "userPhoneNumberSend";
  $.ajax({
    url: "pages/userAction.php",
    type: "POST",

    data: {
      phoneNumber: phoneNumber,
      check: check,
    },
    success: function (response) {
      if (response == "success") {
        var phone = "'" + phoneNumber + "'";
        var htmlLogin =
          '<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="getOtp" name="getOtp"              placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><p id="countDown">OTP has been send!  <span id="time"></span></p><a class="" id="resendField" onclick="resendOTP(' +
          phone +
          ')">Resend OTP</a><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkOTP(' +
          phone +
          ')">Log in</button></div>';
        // console.log(htmlLogin);
        $("#loginDiv").html(htmlLogin);

        $("#resendField").hide();
        timmer();
        setTimeout(function () {
          $("#countDown").hide();
          $("#resendField").show();
        }, 60000);
        // alertMessage("Some thing is wrong..!");
      }
      // alertMessageSuccess("OTP Send Success..");
    },
  });
}

// checkOTP
function checkOTP(phone) {
  var otpCode = $("#getOtp").val();
  var check = "otpCheck";
  if (otpCode != "") {
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

        if (response == "success") {
          // window.location.href = "page-account.php";
          location.reload();
        } else {
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

function updateUserData(userPhone) {
  var fullName = $("#fullName").val();
  var emailAddress = $("#emailAddress").val();
  var userAddress = $("#userAddress").val();
  var flag = 1;
  var check = "userProfileUpdate";
  if (fullName == "") {
    $("#fullName").css({ border: "1px solid red" });
    flag = 0;
  }
  if (emailAddress == "") {
    $("#emailAddress").css({ border: "1px solid red" });
    flag = 0;
  }
  if (userAddress == "") {
    $("#userAddress").css({ border: "1px solid red" });
    flag = 0;
  }
  if (flag == 1) {
    $.ajax({
      url: "pages/userAction.php",
      type: "POST",

      data: {
        fullName: fullName,
        emailAddress: emailAddress,
        userAddress: userAddress,
        userPhone: userPhone,
        check: check,
      },
      success: function (response) {
        if (response == "success") {
          alertMessageSuccess("Update Saved.");
          $("#accountDiv").load(" #accountDiv > *");
        } else {
          alertMessage("Somthing is wrong");
          $("#accountError").html(response);
        }
      },
    });
  }
}

// loginUserFororder
function loginUserFororder() {
  var check = "loginpopupview";
  $.ajax({
    url: "pages/userAction.php",
    type: "POST",

    data: {
      check: check,
    },
    success: function (response) {
      $("#modalDiv").html(response);
    },
  });
}

// placeorder
function placeorder(phoneNumber, token) {
  var paymentMethod = $("input[name='payment_option']:checked").val();
  var name = $("#name").val();
  var address = $("#address").val();
  var area = $("#area").val();
  var phone = $("#phone").val();
  var town = $("#town").val();
  var additionalPhone = $("#additionalPhone").val();
  var additionalInfo = $("#additionalInfo").val();
  var flag = 1;
  var check = "placeOrder";
  if (name == "") {
    $("#name").css({ border: "1px solid red" });
    flag = 0;
  }
  if (address == "") {
    $("#address").css({ border: "1px solid red" });
    flag = 0;
  }
  if (area == "") {
    $("#area").css({ border: "1px solid red" });
    flag = 0;
  }
  if (phone == "") {
    $("#phone").css({ border: "1px solid red" });
    flag = 0;
  }
  if (town == "") {
    $("#town").css({ border: "1px solid red" });
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
        check: check,
      },
      success: function (response) {
        // console.log(response);
        if (response == "success") {
          alertMessageSuccess("Order Place Success.");
          setTimeout(function () {
            cartItem();
            window.location.replace("page-account.php");
          }, 1000);
        }
      },
    });
  }
}

// searchItem
function searchItem(productSearchItem) {
  var categoryName = $("#category_name").val();
  var check = "searchItem";
  if (productSearchItem.length > 2) {
    $.ajax({
      url: "pages/searchAction.php",
      type: "POST",

      data: {
        categoryName: categoryName,
        productSearchItem: productSearchItem,
        check: check,
      },
      success: function (response) {
        $("#searchBox").fadeIn("slow");
        $("#searchBox").html(response);

        window.addEventListener("click", function (e) {
          if (document.getElementById("searchBox").contains(e.target)) {
          } else {
            $("#searchBox").hide();
          }
        });
      },
    });
  } else {
    $("#searchBox").hide();
  }
}

function viewAllItem(categoryId, itemString) {
  window.location.replace(
    "/nest-frontend/shop-grid-right.php?product_name=" +
      itemString +
      "&category=" +
      categoryId
  );
  // var check = "viewAllItem";
  // $.ajax({
  //   url: "pages/searchAction.php",
  //   type: "POST",

  //   data: {
  //     categoryId: categoryId,
  //     itemString:itemString,
  //     check: check
  //   },
  //   success: function (response) {
  //     $('#myTabContent').html(response);

  //   },
  // });
}

// viewOrderDetails
function viewOrderDetails(orderNumber) {
  var check = "checkorderditails";
  $.ajax({
    url: "pages/orderAction.php",
    type: "POST",

    data: {
      orderNumber: orderNumber,
      check: check,
    },
    success: function (response) {
      $("#orderdata").html(response);
    },
  });
}

function orderDiv() {
  $("#orders").load(" #orders > *");
}

// searchProductMobile
function searchProductMobile(searchString) {
  var check = "searchItemMobile";
  if (searchString.length > 2) {
    $.ajax({
      url: "pages/searchAction.php",
      type: "POST",

      data: {
        searchString: searchString,
        check: check,
      },
      success: function (response) {
        if (response != "") {
          $("#searchResultBox").fadeIn("slow");
          $("#searchResultBox").html(response);
        }

        window.addEventListener("click", function (e) {
          if (document.getElementById("searchResultBox").contains(e.target)) {
          } else {
            $("#searchResultBox").hide();
          }
        });
      },
    });
  } else {
    $("#searchResultBox").hide("");
  }
  // $("#searchResultBox").html(searchString);
}

function viewAllItemMobile(searchSrt) {
  window.location.replace(
    "/nest-frontend/shop-grid-right.php?product_name=" +
      searchSrt +
      "&category="
  );
}

function showHideRow(row, flagid, orderProductID, orderNumber) {
  $("#" + row).toggle();
  var check = "itemDetails";
  $.ajax({
    url: "pages/orderAction.php",
    type: "POST",

    data: {
      orderProductID: orderProductID,
      orderNumber: orderNumber,
      check: check,
    },
    success: function (response) {
      $("#itemDetails" + flagid).html(response);
    },
  });
}

// OrderCancel

function OrderCancel(orderId) {
  var check = "cancelFullOrder";
  $.ajax({
    url: "pages/orderAction.php",
    type: "POST",

    data: {
      orderId: orderId,
      check: check,
    },
    success: function (response) {
      if (response == "success") {
        alertMessageSuccess("Order Cancel Success.");
        $("#orderdata").load(" #orderdata > *");
      }
    },
  });
}

// deleteOrderItem
function deleteOrderItem(itemId) {
  var check = "deleteOrderItem";
  $.ajax({
    url: "pages/orderAction.php",
    type: "POST",

    data: {
      itemId: itemId,
      check: check,
    },
    success: function (response) {
      if (response == "success") {
        alertMessageSuccess("Item Success.");
        $("#" + itemId).hide();
      }
    },
  });
}

// updatePhone
function updatePhone()
{
  var newPhone = $("#newPhone").val();
  if ($.isNumeric(newPhone)) {
    var phoneReg = new RegExp(/(^(\+88|0088)?(01){1}[56789]{1}(\d){8})$/);
    if (!phoneReg.test(newPhone)) {
      $("#phoneError").html("Please Enter a Valid Number");
    } else {
      $("#phoneError").html("");
      var check = "userPhoneUpOTP";
      $.ajax({
        url: "pages/userAction.php",
        type: "POST",

        data: {
          newPhone: newPhone,
          check: check,
        },
        success: function (response) {
          console.log(response);
          if (response == "success") {
            var phone = "'" + newPhone + "'";
            var htmlgetOtp =
              '<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="getOtp" name="getOtp"           placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkOTP(' +
              phone +
              ')">Log in</button></div>';
            $("#phoneField").html(htmlgetOtp);
            $("#phoneField").hide();
            timmer();
            setTimeout(function () {
              $("#countDown").hide();
              $("#phoneField").show();
            }, 60000);
          } else {
            $("#phoneError").html(response);
          }
        },
      });
    }
  } else {
    $("#errorNumMessage").html("Please Enter a Valid Number");
  }
}

// changePhone
function changePhone(oldPhone)
{
  // var oldPhone = "'" + oldPhoneNum + "'";
  var html='<input id="oldphone" type="text" value="'+oldPhone+'"><div class="container"><div class="row"><div class="col-xl-12 col-lg-12 col-md-12 m-auto"><div class="row"><div class="col-lg-6 pr-30 d-none d-lg-block"><img class="border-radius-15" src="assets/imgs/page/login-1.png" alt=""></div><div class="col-lg-6 col-md-8"><div class="login_wrap widget-taber-content background-white"> <div class="padding_eight_all bg-white" id="loginDiv"><div class="heading_s1"><h1 class="mb-5">Change Phone Number</h1></div><div class="form-group"> <input type="text" required="" id="newPhoneNumber" name="newPhoneNumber" placeholder="Enter your new phone number*"><small class="text-danger" id="errorNumMessage"></small></div><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="sendOTP()">Send</button></div> </div></div></div> </div></div></div></div>';
  $("#modalDiv").html(html);
}

function sendOTP()
{
  var check = "userPhoneUpOTP";
  var newPhone = $("#newPhoneNumber").val();
  var hh = "oooo";
  if ($.isNumeric(newPhone)) {
    var phoneReg = new RegExp(/(^(\+88|0088)?(01){1}[56789]{1}(\d){8})$/);
    if (!phoneReg.test(newPhone)) {
      $("#errorNumMessage").html("Please Enter a Valid Number");
    } else {
      $("#errorNumMessage").html("");
      $.ajax({
        url: "pages/userAction.php",
        type: "POST",
    
        data: {
          newPhone: newPhone,
          check: check,
        },
        success: function (response) {
          if (response == "success") {
            var phoneNumber = "'" + newPhone + "'";
            var htmlgetOtp =
              '<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="userOTP" name="userOTP" placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><p id="countDown">OTP has been send! <span id="time"></span></p><a class="" id="resendField" onclick="PhoneResendOTP('+phoneNumber+
              ')">Resend OTP</a><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkNewOTP('+phoneNumber +')">Log in</button></div>';
            $("#loginDiv").html(htmlgetOtp);
            $("#resendField").hide();
            timmer();
            setTimeout(function () {
              $("#countDown").hide();
              $("#resendField").show();
            }, 60000);
          } else {
            $("#errorNumMessage").html(response);
          }
        },
      });
    }
  }
}

function PhoneResendOTP(updatePhone)
{

  // console.log(updatePhone);
  var newPhone = updatePhone;
  var check = "userPhoneUpOTP";
  $.ajax({
    url: "pages/userAction.php",
    type: "POST",

    data: {
      newPhone: newPhone,
      check: check,
    },
    success: function (response) {
      if (response == 'success')
      {
        var phoneNumber = "'" + newPhone + "'";
         var htmlgetOtp =
          '<div class="heading_s1"><h1 class="mb-5">Enter Your Otp</h1></div><div class="form-group"><input type="text" required="" id="userOTP" name="userOTP" placeholder="Enter Your OTP*" /><small class="text-danger" id="errorNumMessage"></small></div><p id="countDown">OTP has been send! <span id="time"></span></p><a class="" id="resendField" onclick="PhoneResendOTP('+phoneNumber +')">Resend OTP</a><div class="form-group"><button type="submit" class="btn btn-heading btn-block hover-up" name="login" onclick="checkNewOTP('+phoneNumber+')">Log in</button></div>';
        $("#loginDiv").html(htmlgetOtp);
        $("#resendField").hide();
        timmer();
        setTimeout(function () {
          $("#countDown").hide();
          $("#resendField").show();
        }, 60000);
        }
     
    },
  });
}

function checkNewOTP(newNumber)
{
  var oldNumber = $("#oldphone").val();
  var otp = $("#userOTP").val();
  var check = "checkUpdateOTP";
  if (otp != "") {
    $.ajax({
      url: "pages/userAction.php",
      type: "POST",

      data: {
        otp: otp,
        newNumber: newNumber,
        oldNumber: oldNumber,
        check: check,
      },
      success: function (response) {
        console.log(response);

        if (response == "success") {
          window.location.href = "logout.php";
          // location.reload('logout.php');
        } else {
          $("#errorNumMessage").html(response);
        }

        // $("#productItemField").html(response);
      },
    });
  } else {
    $("#errorNumMessage").html("Please enter OTP number.");
  }

}