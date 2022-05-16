<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "haha"
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

        $shopName = $_POST["shopName"];
        $shopCategory = $_POST["shopCategory"];
        $shopLatitude = doubleval($_POST["shopLatitude"]);
        $shopLongitude = doubleval($_POST["shopLongitude"]);
        $userID = $_POST["userID"];
        $userPhone = $_POST["userPhone"];

        // check the form of Latitude
        if (!preg_match('/^-?(?:\d+|\d*\.\d+)$/', strval($_POST["shopLatitude"])) || $shopLatitude > 90.0 || $shopLatitude < -90.0) {
            throw new Exception("Please make sure the latitude is a number in [-90.0, 90.0].");
        }
        // check the form of Longitude
    
        if (!preg_match('/^-?(?:\d+|\d*\.\d+)$/', strval($_POST["shopLongitude"])) || $shopLongitude > 180.0 || $shopLongitude < -180.0) {
            throw new Exception("Please make sure the longitude is a number in [-180.0, 180.0].");
        }
        
        // check whether shop name have be register
        $stmt = $conn->prepare("select name from store where name= :shopName");
        $stmt -> execute(array("shopName" => $shopName));
        if ($stmt -> rowCount() != 0) {

            throw new Exception("The shop name has been used.");
        }
        

        // insert the data in the sho[]
        $stmt = $conn->prepare (
                                "insert into store (
                                    name, 
                                    position_longitude, 
                                    position_latitude, 
                                    phone_number, 
                                    category, 
                                    UID
                                ) 
                                values (
                                    :shopName, 
                                    :position_longitude, 
                                    :position_latitude, 
                                    :phone_number, 
                                    :category, 
                                    :UID
                                )"
                            );
        $stmt = $stmt->execute(array("shopName" => $shopName, "position_longitude" => $shopLongitude, "position_latitude" => $shopLatitude, "phone_number" => $userPhone, "category" => $shopCategory, "UID" => $userID));
  

        $stmt = $conn->prepare("update user set status = :status where user.ID = :ID");
        $stmt -> execute(array("status" => "owner", "ID" => $userID));
        
        echo json_encode(array("error" => false, "text" => "success"));
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
    