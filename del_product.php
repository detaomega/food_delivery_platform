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
        $ID = $_POST["ID"];
        $stmt = $conn -> prepare("delete from product where product.ID = :ID");
        $stmt -> execute(array("ID" => $ID));
        echo "<script>alert(\"delete sucessful !!\"); window.location.replace(\"nav.php#menu1\");</script>";

    } 
    catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
    }
?>