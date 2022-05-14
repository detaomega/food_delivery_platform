<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        if (!isset($_POST["value"])) exit();
        $emptyField = "";
        if (empty($_POST["value"])) $emptyField = $emptyField . "Value, ";
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

        $value = $_POST["value"];
        if (!preg_match('/^[0-9]+$/', $_POST["value"]) && !preg_match('/^[\-][0-9]+$/', $_POST["value"])) {
            throw new Exception("Please make sure the value is an integer.");
        }
        
        $stmt=$conn->prepare("select wallet from user where account=:account");
        $stmt->execute(array("account" => $_SESSION["account"]));
        $row = $stmt->fetch();
        $value = $value + $row["wallet"];
        if ($value < 0) {
            throw new Exception("Not enough money.");
        }

        $stmt = $conn->prepare("update user set wallet = :value where user.account = :account");
        $stmt->execute(array("value" => $value, "account" => $_SESSION["account"]));
        echo json_encode(array("error" => false, "text" => "success"));
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
    
?>