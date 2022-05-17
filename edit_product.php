<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

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
            throw new Exception("the price show be none negative");
        }
        $ID = $_POST["ID"];
    //     // check quantity is none negative
        if (!preg_match('/^\d+$/', $productQuantity)) {
            throw new Exception("the quantity show be none negative");
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