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
        $OID = $_POST["OID"];
        $stTime = date("Y-m-d H:i:s");
        $stmt = $conn -> prepare("select * from `order` where ID=:OID");
        $stmt -> execute(array("OID" => $OID));
        $row = $stmt -> fetch();
        if ($row["status"] != "Not finish") {
            throw new Exception("The order is already finished or canceled.");
        }

        $stmt = $conn->prepare("update `order` set status = :status, finish_time = :time where ID = :OID");
        $stmt->execute(array("status" => "Finished", "time" => $stTime, "OID" => $OID));
        echo "<script>alert(\"finish order!!\"); window.location.replace(\"nav.php\");</script>";
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }


?>