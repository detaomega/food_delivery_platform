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
    
    try {

        

        $emptyField = "";
        
        
        if (empty($_POST["productPrice"])) $emptyField = $emptyField . "product price, ";
        if (empty($_POST["productQuantity"])) $emptyField = $emptyField . "product quantity, ";
        
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

        $productPrice = $_POST["productPrice"];
        $productQuantity = $_POST["productQuantity"];
        if (!preg_match('/^\d+$/', $productPrice)) {
            throw new Exception("the price should be none negative");
        }
        $ID = $_POST["ID"];
    //     // check quantity is none negative
        if (!preg_match('/^\d+$/', $productQuantity)) {
            throw new Exception("the quantity should be none negative");
        }

        $PID = $_POST["ID"];
        $stmt = $conn -> prepare("select * from contains where PID = :PID");
        $stmt -> execute(array("PID" => $PID));
        while ($row = $stmt -> fetch()) {
            $OID = $row["OID"];
            $orderStmt = $conn -> prepare("select * from `order` where ID = :OID");
            $orderStmt -> execute(array("OID" => $OID));
            $orderInfo = $orderStmt -> fetch();
            if ($orderInfo["status"] == "Not finish") {
                throw new Exception("Please finish the order containing this product before modifying it.");
            }
        }

        $stmt = $conn->prepare("update product set price = :price, quantity = :quantity where product.ID = :ID");
        $stmt->execute(
            array (
                "price" => $productPrice, 
                "quantity" => $productQuantity, 
                "ID" => $ID
            )
        );
        
        echo "<script>alert(\"sucessful !!\"); window.location.replace(\"nav.php#menu1\");</script>";


    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#menu1\");</script>";
    }
    // echo "<script>alert(\"fuck\"); window.location.replace(\"nav.php#menu1\");</script>";
?>