<?php  
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";
    date_default_timezone_set("Asia/Taipei");

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $OID = $_POST["OID"];
        $stmt = $conn -> prepare("select * from `order` where ID=:OID");
        $stmt -> execute(array("OID" => $OID));
        $row = $stmt -> fetch();
        if ($row["status"] != "Not finish") {
            throw new Exception("The order is already finished or canceled.");
        }

        $stmt = $conn->prepare("update `order` set status = :status where ID = :OID");
        $stmt->execute(array("status" => "Finished", "OID" => $OID));
        echo "<script> window.location.replace(\"nav.php\");</script>";
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }


?>