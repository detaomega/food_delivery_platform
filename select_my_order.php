<?php  
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";
    date_default_timezone_set("Asia/Taipei");
    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated'] != true) {
        header("Location: index.php");
        exit();
    }

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mode = $_GET["mode"];
    try {
        $prefix = "";
        if ($mode == "#tab_1") {
            $prefix = "first";
            
        }
        else if ($mode == "#tab_3") {
            $prefix = "second";
        }
        else {
            echo "<script>alert(\"you don't selected any order.\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        $UID = $_GET["UID"];
        $stmt = $conn -> prepare("select * from `order` where UID=:UID");
        $stmt -> execute(array("UID" => $UID));
        $result = $stmt -> fetchAll();
        $type = $_GET["buttonValue"];
        $errorMessage = "";
        $hasError = false;
        $cnt=1;
        foreach ($result as &$rowOrder) {
            $OID = $rowOrder['ID'];
            
            if ($_GET["$prefix$OID"] != "yes") {
                continue;
            }
            $stTime = date("Y-m-d H:i:s");
            $stmt = $conn -> prepare("select * from `order` where ID=:OID");
            $stmt -> execute(array("OID" => $OID));
            $row = $stmt -> fetch();
            if ($row["status"] != "Not finish") {
                $errorMessage = $errorMessage . $OID . ", ";
                $hasError = true;
                continue;
            }
            $total = $row["payment"];
            $SID = $row["SID"];
            $UID = $row["UID"];
            $stmt=$conn->prepare("select UID from store where ID=:SID");
            $stmt->execute(array("SID" => $SID));
            $row = $stmt->fetch();
            $SUID = $row["UID"];

            //products
            $stmt = $conn -> prepare("select * from `contains` where OID=:OID");
            $stmt -> execute(array("OID" => $OID));
            while ($row = $stmt -> fetch()) {
                $PHID = $row["PHID"];
                $productStmt = $conn -> prepare("select * from `product_history` where ID=:PHID");
                $productStmt -> execute(array("PHID" => $PHID));
                $productInfo = $productStmt -> fetch();
                $PID = $productInfo["PID"];
                $productStmt = $conn -> prepare("select * from `product` where ID=:PID");
                $productStmt -> execute(array("PID" => $PID));
                if ($productStmt -> rowCount() == 0) continue;
                $productInfo = $productStmt -> fetch();
                $newQuantity = $productInfo["quantity"] + $row["number"];
                $productStmt = $conn->prepare("update `product` set quantity = :quantity where ID = :PID");
                $productStmt->execute(array("quantity" => $newQuantity, "PID" => $PID));
            }

            //wallet
            $stmt = $conn -> prepare("select wallet from user where ID=:ID");
            $stmt -> execute(array("ID" => $UID));
            $userInfo = $stmt -> fetch();
            $newValue = $userInfo["wallet"] + $total;  
            $stmt = $conn -> prepare("update user set wallet =:value where user.ID=:ID");
            $stmt -> execute(array("value" => $newValue, "ID" => $UID));

            $stmt = $conn -> prepare("select wallet from user where ID=:ID");
            $stmt -> execute(array("ID" => $SUID));
            $userInfo = $stmt -> fetch();
            $newValue = $userInfo["wallet"] - $total; 
            $stmt = $conn -> prepare("update user set wallet =:value where user.ID=:ID");
            $stmt -> execute(array("value" => $newValue, "ID" => $SUID));

            //transaction
            $stmt = $conn->prepare("insert into `transaction` (`type`, `price`, `time`, `UID`, `target_UID`, `is_refund`) VALUES (:type, :price, :time, :UID, :target_UID, :is_refund)");
            $stmt->execute(array("type" => "Takings", "price" => $total, "time" => $stTime, "UID" => $UID, "target_UID" => $SUID, "is_refund" => 1));

            $stmt = $conn->prepare("insert into `transaction` (`type`, `price`, `time`, `UID`, `target_UID`, `is_refund`) VALUES (:type, :price, :time, :UID, :target_UID, :is_refund)");
            $stmt->execute(array("type" => "Payment", "price" => $total, "time" => $stTime, "UID" => $SUID, "target_UID" => $UID, "is_refund" => 1));

            //order status, time
            $stmt = $conn->prepare("update `order` set status = :status, finish_time = :time where ID = :OID");
            $stmt->execute(array("status" => "Cancel", "time" => $stTime, "OID" => $OID));
            $cnt = $cnt + 1;
        }
        if ($hasError) {
            $errorMessage = $errorMessage . "order is already finished or canceled.";
            echo "<script>alert(\"$errorMessage\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        else if ($cnt != 1) {
            echo "<script>alert(\"Cancel selected order\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        else {
            echo "<script>alert(\"you don't selected any order.\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
    }
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }

?>