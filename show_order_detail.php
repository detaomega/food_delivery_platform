<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";
    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated'] != true) {
        header("Location: index.php");
        exit();
    }

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // product part
    $OID = $_POST["OID"];
    $stmt = $conn -> prepare("select * from contains where OID=:OID");
    $stmt -> execute(array("OID" => $OID));
    $result = $stmt -> fetchAll();

    $stmt = $conn -> prepare("select * from `order` where ID=:OID");
    $stmt -> execute(array("OID" => $OID));
    $ORDER = $stmt -> fetch();
    $distance = $ORDER["distance"];
    $payment = $ORDER["payment"];
    $cnt = 1;
    echo <<< EOT
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="navbar-brand ">Order Details</div>
                </div>
            </div>
        </nav>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="nav.php#myOrder">
                        <button type="button " style="margin-top: 8px; margin-left: auto; position: absolute; right: 1%;" class=" btn btn-info " data-toggle="modal" id="signOutBtn">back</button>
                    </a>
                    <h4 class="modal-title">Order</h4>
                </div>

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
        $PHID = $row['PHID'];
        $number = $row['number'];
        $stmt = $conn -> prepare("select * from product_history where ID=:PHID");
        $stmt -> execute(array("PHID" => $PHID));
        $product = $stmt -> fetch();
        $picture = $product["image"];
        $price = $product["price"];
        $picture_type = $product['picture_type'];
        $img = $product["image"];
        $name = $product["name"];
        echo <<< EOT
                                <tr>
                                    <th scope="row">$cnt</th>
                                    <td><img style="max-width:50%; max-height:100px" src="data:$picture_type;base64,$picture"  alt="$name"/></td>
                                    <td>$name</td>
                                    <td>$price </td>
                                    <td>$number</td>
                                </tr>
        EOT;
        $cnt++;
    }  
    $subTotal = $payment - $subTotal;
    echo <<< EOT
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                    <div><label>Subtotal $$subTotal</label></div>  
                    <div><label>Delivery Fee $$distance</label></div>  
                    <div><label>Total Price $$payment</label></div>  
                </div>
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