<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        if (!isset($_POST["shopName"]) || !isset($_POST["shopCategory"]) || !isset($_POST["shopLatitude"]) || !isset($_POST["shopLongitude"])) exit();
        $emptyField = "";
        if (empty($_POST["shopName"])) $emptyField = $emptyField . "shop name, ";
        if (empty($_POST["shopCategory"])) $emptyField = $emptyField . "shop category, ";
        if (empty($_POST["shopLatitude"])) $emptyField = $emptyField . "latitude, ";
        if (empty($_POST["shopLongitude"])) $emptyField = $emptyField . "longitude, ";
        
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }
        throw new Exception($emptyField . "should not be empty.");

        // $latitude = $_POST["latitude"];
        // $longitude = $_POST["longitude"];
        // //latitude
        // if (!preg_match('/^-?(?:\d+|\d*\.\d+)$/', strval($_POST["latitude"])) || $latitude > 90.0 || $latitude < -90.0) {
        //     throw new Exception("Please make sure the latitude is a number in [-90.0, 90.0].");
        // }
        // //longitude
        // if (!preg_match('/^-?(?:\d+|\d*\.\d+)$/', strval($_POST["longitude"])) || $longitude > 180.0 || $longitude < -180.0) {
        //     throw new Exception("Please make sure the longitude is a number in [-180.0, 180.0].");
        // }
        
        // $stmt = $conn->prepare("update user set position_longitude = :long, position_latitude = :lat where user.account = :account");
        // $stmt->execute(array("long" => $longitude, "lat" => $latitude, "account" => $_SESSION["account"]));
        // echo json_encode(array("error" => false, "text" => "success"));
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
    
?>