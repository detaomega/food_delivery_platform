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
        $PID = $_POST["ID"];
        $stmt = $conn -> prepare("select * from contains where PID = :PID");
        $stmt -> execute(array("PID" => $PID));
        while ($row = $stmt -> fetch()) {
            $OID = $row["OID"];
            $orderStmt = $conn -> prepare("select * from `order` where ID = :OID");
            $orderStmt -> execute(array("OID" => $OID));
            $orderInfo = $orderStmt -> fetch();
            if ($orderInfo["status"] == "Not finish") {
                throw new Exception("Please finish the order containing this product before deleting it.");
            }
        }

        $stmt = $conn -> prepare("delete from product where product.ID = :ID");
        $stmt -> execute(array("ID" => $PID));
        echo "<script>alert(\"delete sucessful !!\"); window.location.replace(\"nav.php#menu1\");</script>";

    } 
    catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
    }
?>