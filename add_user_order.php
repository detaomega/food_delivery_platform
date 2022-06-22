<?php  
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // product part
        $distance = $_POST["distance"];
        $total = $_POST["total"];
        $SID = $_POST["SID"];
        $UID = $_POST["UID"];
        $stTime = date("Y-m-d H:i:s");
        $mode = $_POST["mode"];
        $stmt = $conn->prepare (
            "insert into `order` (
                status, 
                start_time, 
                payment,
                type,
                SID,
                UID,
                distance
            ) 
            values (
                :status,  
                :start_time, 
                :payment, 
                :type,
                :SID,
                :UID,
                :distance
            )"
        );
        $stmt->execute(
            array (
                "status" => "Not finish",
                "start_time" => $stTime, 
                "payment" => $total,
                "type" => $mode,
                "SID" => $SID,
                "UID" => $UID,
                "distance" => $distance
            )
        );
        $OID = $conn->lastInsertId();
    
        // user wallet 
        $stmt = $conn -> prepare("select wallet from user where ID=:ID");
        $stmt -> execute(array("ID" => $UID));
        $userInfo = $stmt -> fetch();
        $newValue = $userInfo["wallet"] - $total;  
        $stmt = $conn -> prepare("update user set wallet =:value where user.ID=:ID");
        $stmt -> execute(array("value" => $newValue, "ID" => $UID));
           
        // add contains from OID and PID
        $stmt = $conn -> prepare("select * from product where SID=:SID");
        $stmt -> execute(array("SID" => $SID));
        $result = $stmt -> fetchAll();
        foreach ($result as &$row) {
            $ID = $row['ID'];
            $orderQuantity = $_POST["$ID"];
            if ($orderQuantity == 0) {
                continue;
            }
            $stmt = $conn -> prepare (
                "insert into `contains` (
                    OID,
                    PID,
                    number
                ) 
                values (
                    :OID,  
                    :PID, 
                    :number 
                )"
            );
            $stmt->execute(
                array (
                    "OID" => $OID,
                    "PID" => $ID, 
                    "number" => $orderQuantity
                )
            );
        }         
        echo "<script>alert(\"success!!\"); window.location.replace(\"nav.php\");</script>";
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }


?>