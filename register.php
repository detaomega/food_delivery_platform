<?php
    session_start();
    $_SESSION["Authenticated"] = false;
    $_SESSION["Typed"] = false;

    $dbservername = "localhost";
    $dbname = "DB_HW";
    $dbusername = "dev";
    $dbpassword = "devpasswd";//make sure permission is correctly set on phpmyadmin

    try {
        //check if variables are set
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

        //check if variables are empty
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
        //throw empty error messege
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

        //initializing variables and connecting to database
        $name = $_POST["name"];
        $phonenumber = $_POST["phonenumber"];
        $account = $_POST["account"];
        $password = $_POST["password"];
        $repassword = $_POST["re-password"];
        $latitude = doubleval($_POST["latitude"]);
        $longitude = doubleval($_POST["longitude"]);
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //checking if the account already exists
        $stmt = $conn->prepare("select account from user where account=:account");
        $stmt->execute(array("account" => $account));
        if ($stmt->rowCount() != 0) {
            throw new Exception("The account has been registered.");
        }

        //checking if the input data is in the correct format
        //name
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            throw new Exception("Please make sure the name only consist of English letters or whitespaces.");
        }
        //phonenumber
        if (!preg_match('/^[0-9]+$/', $phonenumber) || strlen($phonenumber) != 10) {
            throw new Exception("Please make sure the phonenumber is a sequence of 10 numbers.");
        }
        //account
        if (!preg_match('/^[a-zA-Z0-9]+$/', $account)) {
            throw new Exception("Please make sure the account only consist of English letters or numbers.");
        }
        //password
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            throw new Exception("Please make sure the password only consist of English letters or numbers.");
        }
        if ($password != $repassword) {
            throw new Exception("Please make sure the re-type password is the same as the password.");
        }
        //latitude
        if (!preg_match('/^[0-9\.]+$/', strval($_POST["latitude"])) || $latitude > 90.0 || $latitude < -90.0) {
            throw new Exception("Please make sure the latitude is a number in [-90.0, 90.0].");
        }
        //longitude
        if (!preg_match('/^[0-9\.]+$/', strval($_POST["longitude"])) || $longitude > 180.0 || $longitude < -180.0) {
            throw new Exception("Please make sure the longitude is a number in [-180.0, 180.0].");
        }

        //inserting into database and returning to sign in page
        $salt = strval(rand(1000, 9999));
        $hashvalue = hash("sha256", $salt.$password);
        $stmt = $conn->prepare("insert into user (account, password, name, status, phone_number, wallet, position_longitude, position_latitude, salt) values (:account, :password, :name, :status, :phone_number, :wallet, :position_longitude, :position_latitude, :salt)");
        $stmt->execute(array("account" => $account, "password" => $hashvalue, "name" => $name, "status" => "normal_user", "phone_number" => $phonenumber, "wallet" => 0, "position_longitude" => $longitude, "position_latitude" => $latitude, "salt" => $salt));
        echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("Successfully registered!");
            window.location.replace("index.php");
        </script>
        </body>
        </html> 
        EOT;

    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo <<< EOT
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