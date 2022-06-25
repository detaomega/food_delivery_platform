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

    try {

        // check wheather store has change the product.
        $stmt = $conn -> prepare("select version from store where ID=:SID");
        $stmt -> execute(array("SID" => $_POST["SID"]));
        $storeInfo = $stmt -> fetch();
        $version = $storeInfo["version"];
        $oldversion = $_POST["version"];
        if ($version != $oldversion) {
            echo "<script>alert(\"該店家剛剛改變了他們的菜單哈哈\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }

        $stmt = $conn -> prepare("select * from product where SID=:SID");
        $stmt -> execute(array("SID" => $_POST["SID"]));
        $result = $stmt -> fetchAll();
        foreach ($result as &$row) {
            $ID = $row['ID'];
            $quantity = $row["quantity"];
            $orderQuantity = $_POST["$ID"];
            $quantity = $quantity - $orderQuantity;
            if ($orderQuantity == 0) {
                continue;
            }   
            else if ($quantity < 0) {
                echo "<script>alert(\"訂單數量大於商家提供範圍!!\"); window.location.replace(\"nav.php\");</script>";
                exit();
            }           
        }

        $deliveryFee = $_POST["deliveryFee"];
        $total = $_POST["total"];
        $SID = $_POST["SID"];
        $UID = $_POST["UID"];
        $stTime = date("Y-m-d H:i:s");
        $mode = $_POST["mode"];
        $stmt=$conn->prepare("select UID from store where ID=:SID");
        $stmt->execute(array("SID" => $SID));
        $row = $stmt->fetch();
        $SUID = $row["UID"];

        $stmt = $conn -> prepare("select wallet from user where ID=:ID");
        $stmt -> execute(array("ID" => $UID));
        $userInfo = $stmt -> fetch();
        $newValue = $userInfo["wallet"] - $total;
        if ($newValue < 0) {
            echo "<script>alert(\"你的餘額不足\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }

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
                :deliveryFee
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
                "deliveryFee" => $deliveryFee
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

        $stmt = $conn -> prepare("select wallet from user where ID=:ID");
        $stmt -> execute(array("ID" => $SUID));
        $userInfo = $stmt -> fetch();
        $newValue = $userInfo["wallet"] + $total;  
        $stmt = $conn -> prepare("update user set wallet =:value where user.ID=:ID");
        $stmt -> execute(array("value" => $newValue, "ID" => $SUID));
        
        // add contains from OID and PID
        $stmt = $conn -> prepare("select * from product where SID=:SID");
        $stmt -> execute(array("SID" => $SID));
        $result = $stmt -> fetchAll();
        foreach ($result as &$row) {
            $ID = $row['ID'];
            $quantity = $row["quantity"];
            $orderQuantity = $_POST["$ID"];
            $quantity = $quantity - $orderQuantity;
            if ($orderQuantity == 0) {
                continue;
            }

            // update quantity of product
            $stmt = $conn -> prepare("update product set quantity =:value where ID =:PID");
            $stmt -> execute(array("value" => $quantity, "PID" => $ID));

            // save to product_history
            $stmt = $conn -> prepare("select * from product where ID =:PID");
            $stmt -> execute(array("PID" => $ID));
            $productInfo = $stmt -> fetch();
            $stmt = $conn->prepare (
                "insert into product_history (
                    name, 
                    image, 
                    price, 
                    quantity, 
                    SID,
                    PID,
                    picture_type
                ) 
                values (
                    :name, 
                    :image, 
                    :price, 
                    :quantity, 
                    :SID,
                    :PID,
                    :picture_type
                )"
            );
            $stmt -> execute(
                array (
                    "name" => $productInfo["name"], 
                    "image" => $productInfo["image"], 
                    "price" => $productInfo["price"], 
                    "quantity" => $productInfo["quantity"],
                    "SID" => $productInfo["SID"],
                    "PID" => $productInfo["ID"],
                    "picture_type" => $productInfo["picture_type"]
                )
            );
            $PHID = $conn->lastInsertId();

            $stmt = $conn -> prepare (
                "insert into `contains` (
                    OID,
                    PHID,
                    number
                ) 
                values (
                    :OID,  
                    :PHID, 
                    :number 
                )"
            );
            $stmt -> execute(
                array (
                    "OID" => $OID,
                    "PHID" => $PHID, 
                    "number" => $orderQuantity
                )
            );
        }         
        //transaction
        $stmt = $conn->prepare("insert into `transaction` (`type`, `price`, `time`, `UID`, `target_UID`) VALUES (:type, :price, :time, :UID, :target_UID)");
        $stmt->execute(array("type" => "Payment", "price" => $total, "time" => $stTime, "UID" => $UID, "target_UID" => $SUID));

        $stmt = $conn->prepare("insert into `transaction` (`type`, `price`, `time`, `UID`, `target_UID`) VALUES (:type, :price, :time, :UID, :target_UID)");
        $stmt->execute(array("type" => "Takings", "price" => $total, "time" => $stTime, "UID" => $SUID, "target_UID" => $UID));
        echo "<script>alert(\"success!!\"); window.location.replace(\"nav.php\");</script>";
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }


?>