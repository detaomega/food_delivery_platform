<?php
    session_start();
    $_SESSION["Authenticated"]=false;
    $_SESSION["Typed"]=false;

    $dbservername="localhost";
    $dbname="DB_HW";
    $dbusername="dev";
    $dbpassword="devpassword";

    try {
        if (!isset($_POST["name"]) || 
            !isset($_POST["phonenumber"]) || 
            !isset($_POST["account"]) || 
            !isset($_POST["password"]) || 
            !isset($_POST["re-password"]) || 
            !isset($_POST["latitude"]) || 
            !isset($_POST["longitude"])) {
            header("Location: sign-up.php");
            exit(); 
        }
        $emptyField = "";
        if (empty($_POST["name"])) $emptyField = $emptyField . "NAME, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedNAME"] = $_POST["name"];
        }
        if (empty($_POST["phonenumber"])) $emptyField = $emptyField . "PHONENUMBER, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedPHONENUMBER"] = $_POST["phonenumber"];
        }
        if (empty($_POST["account"])) $emptyField = $emptyField . "ACCOUNT, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedACCOUNT"] = $_POST["account"];
        }
        if (empty($_POST["password"])) $emptyField = $emptyField . "PASSWORD, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedPASSWORD"] = $_POST["password"];
        }
        if (empty($_POST["re-password"])) $emptyField = $emptyField . "RE-TYPE PASSWORD, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedREPASSWORD"] = $_POST["re-password"];
        }
        if (empty($_POST["latitude"])) $emptyField = $emptyField . "LATITUDE, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedLATITUDE"] = $_POST["latitude"];
        }
        if (empty($_POST["longitude"])) $emptyField = $emptyField . "LONGITUDE, ";
        else {
            $_SESSION["Typed"] = true;
            $_SESSION["TypedLONGITUDE"] = $_POST["longitude"];
        }
        
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

    } catch (Exception $e) {
        $msg=$e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("sign-up.php");
        </script>
        </body>
        </html> 
        EOT;
    }
?>