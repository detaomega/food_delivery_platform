<?php  
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    

    try {
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // product part
        $distance = $_POST["distance"];
        $total = $_POST["total"];
        $SID = $_POST["SID"];
        $UID = $_POST["UID"];
        $stTime = date("Y-m-d H:i:s");
        $mode = $_POST["mode"];
        $stmt = $conn->prepare (
            "insert into order (
                status, 
                start_time, 
                payment,
                type,
                SID,
                UID,
                distance
            ) 
            values (
                :status  
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
        echo "<script>alert(\"$distance $stTime $total $mode $SID $UID $distance\"); window.location.replace(\"nav.php\");</script>";    

        exit();
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php/\");</script>";
    }
    // $stmt = $conn -> prepare("select * from product where SID=:SID");
    // $stmt -> execute(array("SID" => $SID));
    // $result = $stmt -> fetchAll();
  

?>