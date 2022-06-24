<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // product part
    $SID = $_POST["SID"];
    $distance = $_POST["distance"];
    $mode = $_POST["mode"];
    $oldversion = $_POST["version"];
    $stmt = $conn -> prepare("select * from product where SID=:SID");
    $stmt -> execute(array("SID" => $SID));
    $result = $stmt -> fetchAll();
    $deliveryFee = 0;
    $subTotal = 0;
    $cnt = 1;
    if ($stmt -> rowCount() == 0) {
        echo "<script>alert(\"該店家沒有商品\"); window.location.replace(\"nav.php\");</script>";
        exit();
    }
    $stmt = $conn -> prepare("select version from store where ID=:SID");
    $stmt -> execute(array("SID" => $SID));
    $storeInfo = $stmt -> fetch();
    $version = $storeInfo["version"];
    if ($version != $oldversion) {
        echo "<script>alert(\"該店家剛剛改變了他們的菜單哈哈\"); window.location.replace(\"nav.php\");</script>";
        exit();
    }
    // user part get wallet and check whether the total price bigger than user wallet
    $UID = $_POST["UID"];
    $stmt=$conn -> prepare("select wallet from user where ID=:ID");
    $stmt -> execute(array("ID" => $UID));
    $userInfo = $stmt -> fetch();
    $wallet = $userInfo["wallet"];
    foreach ($result as &$row) {
        $ID = $row['ID'];
        $quantity = $row['quantity'];
        $orderQuantity = $_POST["$ID"];
        if ($orderQuantity == 0) {
            continue;
        }
        else if ($orderQuantity > $quantity) {
            echo "<script>alert(\"訂單數量大於商家提供範圍!!\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        $cnt++;
    } 
    if ($cnt == 1) {
        echo "<script>alert(\"你的訂單是空的!!\"); window.location.replace(\"nav.php\");</script>";
        exit();
    }
    $cnt = 1;
    if ($mode == "Delivery") {
        $deliveryFee = round($distance*10);
        if ($deliveryFee < 10) $deliveryFee = 10;
    }
    else {
        $deliveryFee = 0;
    }
    echo <<< EOT
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="navbar-brand ">CheckOutPage</div>
                </div>

            </div>
        </nav>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="nav.php">
                        <button type="button " style="margin-top: 8px; margin-left: auto; position: absolute; right: 1%;" class=" btn btn-info " data-toggle="modal" id="signOutBtn">close</button>
                    </a>
                    <h4 class="modal-title">menu</h4>
                </div>
                <form action="add_user_order.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="  col-xs-12">
                                <table class="table" style=" margin-top: 15px;">
                                    <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Picture</th>                                 
                                        <th scope="col">Meal Name</th>
                                        <th scope="col">price</th>
                                        <th scope="col">Order Quantity</th>
                                        </tr>
                                    </thead>
                                <tbody>
    EOT;
    foreach ($result as &$row) {
        $ID = $row['ID'];
        $name = $row['name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $picture = $row["image"];
        $picture_type = $row['picture_type'];
        $img=$row["image"];
        $logodata = $img;
        $orderQuantity = $_POST["$ID"];
        if ($orderQuantity == 0) {
            continue;
        }
        $subTotal = $subTotal + $orderQuantity * $price;
        echo <<< EOT
                                    <tr>
                                        <th scope="row">$cnt</th>
                                        <td><img style="max-width:50%; max-height:100px" src="data:$picture_type;base64,$picture"  alt="$name"/></td>
                                        <td>$name</td>
                                        <td>$price </td>
                                        <td>$orderQuantity</td>
                                        <input type="hidden" name="$ID" value="$orderQuantity">
                                    </tr>
        EOT;
        $cnt++;
    } 
    // the place need to fixed. 
    $Total = $deliveryFee + $subTotal;
    if ($wallet < $Total) {
        echo "<script>alert(\"你的餘額不足\"); window.location.replace(\"nav.php\");</script>";
        exit();
    }
    echo <<< EOT
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="modal-footer">
                        <div><label>Subtotal $$subTotal</label></div>  
                        <div><label>Delivery Fee $$deliveryFee</label></div>  
                        <div><label>Total Price $$Total</label></div>  
                        <button type="submit" class="btn btn-default" data-dismiss="modal">Order</button>
                        <input type="hidden" name="SID" value="$SID">
                        <input type="hidden" name="total" value="$Total">
                        <input type="hidden" name="UID" value="$UID">
                        <input type="hidden" name="deliveryFee" value="$deliveryFee">
                        <input type="hidden" name="mode" value="$mode">
                        <input type="hidden" name="version" value="$version">
                    </div>
                </form>
            </div>
        </div>
    EOT;
?> 



<!doctype html>
<html lang="en">

    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>CheckOutPage</title>
    </head>
</html>