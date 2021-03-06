<?php
    session_start();
    $dbservername = "localhost";
    $dbname = "DB_HW";
    $dbusername = "dev";
    $dbpassword = "devpasswd";//make sure permission is correctly set on phpmyadmin

    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated'] != true) {
        header("Location: index.php");
        exit();
    }

    try {
        if (!isset($_POST["input"])) throw new Exception("Input is not set.");

        //initializing variables and connecting to database
        $input = $_POST["input"];
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //checking if the store name already exists
        $stmt = $conn->prepare("select name from store where name=:name");
        $stmt->execute(array("name" => $input));
        if ($stmt->rowCount() != 0) {
            echo json_encode(array("error" => false, "used" => true));
        } else {
            echo json_encode(array("error" => false, "used" => false));
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
?>