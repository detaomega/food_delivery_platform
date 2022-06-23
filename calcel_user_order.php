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
        $ID = $_POST["OID"];
        $stmt = $conn -> prepare("update `order` set status = :newStatus where ID = :OID");
        $stmt -> execute(array("newStatus" => "Cancel", "OID" => $ID));
        echo "<script>alert(\"You have cancel your order!!\"); window.location.replace(\"nav.php#menu1\");</script>";
    } 
    catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
    }
?>