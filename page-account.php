<?php
include 'apidata/dataFetch.php';
include 'inc/header.php';
include 'inc/apiendpoint.php';

// include 'inc/function.php';
session_start();
if (getPhone() == '') {
    header("Location: index.php");
}



// user profile data 

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "user.php?phoneNumber=" . getPhone(),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization:" . APIKEY,
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $result = json_decode($response);
    $profileData = $result->data->user[0];
}



// order details


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => APIENDPOINT . "order.php?token=" . getToken(),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization:" . APIKEY,
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $result = json_decode($response);
    $orderData = $result->data->orders;
}


?>

<main class="main pages">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Pages <span></span> My Account
            </div>
        </div>
    </div>
    <div class="page-content pt-150 pb-150">
        <div class="container" id="accountDiv">
            <div class="row">
                <div class="col-lg-10 col-sm-12 m-auto">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="dashboard-menu">
                                <ul class="nav flex-column" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="orders-tab" data-bs-toggle="tab" href="#orders" onclick="orderDiv()" role="tab" aria-controls="orders" aria-selected="false"><i class="fi-rs-shopping-bag mr-10"></i>Orders</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="false"><i class="fi-rs-settings-sliders mr-10"></i>Change Phone</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="account-detail-tab" data-bs-toggle="tab" href="#account-detail" role="tab" aria-controls="account-detail" aria-selected="true"><i class="fi-rs-user mr-10"></i>Account details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="logout.php"><i class="fi-rs-sign-out mr-10"></i>Sign Out</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content account dashboard-content">
                                <div class="tab-pane fade " id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="mb-0"><?php echo getPhone(); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                From your account dashboard. you can easily check &amp; view your <a data-bs-toggle="tab" role="tab" aria-controls="dashboard" aria-selected="false" href="#orders">recent orders</a>,<br />
                                                manage your <a href="#" data-bs-toggle="modal" data-bs-target="#userlogin" onclick="changePhone('<?php echo getPhone(); ?>')">Change Your Phone Number</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane active fade show" id="orders" role="tabpanel" aria-labelledby="orders-tab">

                                    <div id="orderdata">
                                        <?php

                                        ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0">Your Orders</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Order</th>
                                                                <th class="text-center">Date</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // if (!isset($orderData->message)) {
                                                            foreach ($orderData as $orderValue) {
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $orderValue->orderno; ?></td>
                                                                    <td class="text-center"><?php echo $orderValue->orddate; ?></td>
                                                                    <td class="text-center"><?php
                                                                                            if ($orderValue->so_status == 0) {
                                                                                            ?>
                                                                            <span class="badge rounded-pill alert-warning text-warning">Pending</span>
                                                                        <?php
                                                                                            } elseif ($orderValue->so_status == 1) {
                                                                        ?>
                                                                            <span class="badge rounded-pill alert-success text-success">Accept</span>
                                                                        <?php
                                                                                            } elseif ($orderValue->so_status == 2) {
                                                                        ?>
                                                                            <span class="badge rounded-pill alert-secondary text-secondary">Rejected</span>
                                                                        <?php
                                                                                            } elseif ($orderValue->so_status == 3) {
                                                                        ?>
                                                                            <span class="badge rounded-pill alert-danger text-danger">Canceled</span>
                                                                        <?php
                                                                                            } elseif ($orderValue->so_status == 4) {
                                                                        ?>
                                                                            <span class="badge rounded-pill alert-success text-success">Complete</span>
                                                                        <?php
                                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="text-center">
                                                                            <button onclick="viewOrderDetails(<?php echo $orderValue->orderno; ?>)" class="float-start btn btn-small btn-success  m-2">View</button>
                                                                            <?php if (($orderValue->so_status == 0 || $orderValue->so_status == 1)) {
                                                                            ?>
                                                                                <button onclick="OrderCancel(<?php echo $orderValue->orderno; ?>)" class="float-start m-2 btn btn-small bg-danger "><i title="Cancel Order" class="fi-rs-cross"></i></button>
                                                                        </span>
                                                                    <?php
                                                                            } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            // }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="orderinfo">
                                        Hello
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="account-detail" role="tabpanel" aria-labelledby="account-detail-tab">
                                    <div class="card mb-4 border p-3">
                                        <div class="card-header">
                                            <h5>Account Details</h5>
                                            <small id="accountError" class="text-danger"></small>
                                        </div>
                                        <div class="card-body">
                                            <!-- <form method="post" name="enq"> -->

                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label>Full Name <span class="required">*</span></label>
                                                    <input required="" value="<?php echo $profileData->fullName; ?>" class="form-control" name="fullName" id="fullName" />
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Email Address <span class="required">*</span></label>
                                                    <input required="" value="<?php echo $profileData->email; ?>" class="form-control" name="emailAddress" type="email" id="emailAddress" />
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label>Address <span class="required">*</span></label>
                                                    <input required="" class="form-control" name="userAddress" value="<?php echo $profileData->address; ?>" type="text" id="userAddress" />
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-fill-out submit font-weight-bold" onclick="updateUserData('<?php echo getPhone(); ?>')">Save
                                                        Change</button>
                                                </div>
                                            </div>
                                            <!-- </form> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
$pageLavelScript = '<script>
    $(document).ready(function() {
        console.log("ready!");
        orderDiv();
    });
</script>';
?>


<?php include 'inc/footer.php'; ?>