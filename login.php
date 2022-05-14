<?php
	session_start();
    $_SESSION["Authenticated"] = false;
    $_SESSION["TypedLogin"] = false;

    $dbservername = "localhost";
    $dbname = "DB_HW";
    $dbusername = "dev";
    $dbpassword = "devpasswd";//make sure permission is correctly set on phpmyadmin

    try {
        //check if variables are set
        if (!isset($_POST["account"]) || 
            !isset($_POST["password"])) {
            header("Location: index.php");
            exit(); 
        }

        //check if variables are empty
        $emptyField = "";
        if (empty($_POST["account"])) $emptyField = $emptyField . "ACCOUNT, ";
        else {
            $_SESSION["TypedLogin"] = true;
            $_SESSION["TypedLoginACCOUNT"] = $_POST["account"];
        }
        if (empty($_POST["password"])) $emptyField = $emptyField . "PASSWORD, ";
        else {
            $_SESSION["TypedLogin"] = true;
            $_SESSION["TypedLoginPASSWORD"] = $_POST["password"];
        }
        //throw empty error messege
        if (!empty($emptyField)) {
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

        //initializing variables and connecting to database
        $account = $_POST["account"];
        $password = $_POST["password"];
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //checking if the input data is in the correct format
        //account
        if (!preg_match('/^[a-zA-Z0-9]+$/', $account)) {
            throw new Exception("Please make sure the account only consist of English letters or numbers.");
        }
        //password
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            throw new Exception("Please make sure the password only consist of English letters or numbers.");
        }

        //checking if the password is correct
        $stmt=$conn->prepare("select account, password, salt from user where account=:account");
        $stmt->execute(array("account" => $account));

        //checking if the account already exists
        if ($stmt->rowCount() == 0) {
            throw new Exception("The account does not exist. Login failed.");
        } else if ($stmt->rowCount() != 1) {
            throw new Exception("An error occurred. Login failed.");
        }
        $row = $stmt->fetch();
        if ($row['password'] != hash("sha256", $row["salt"].$password)) {
            throw new Exception("Incorrect password. Login failed.");
        }
        
        //Successfully logged in, switch to nav.php
        $_SESSION['Authenticated'] = true; 
        $_SESSION['account'] = $row[0]; 
        header("Location: nav.php"); 
        exit();

    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo <<< EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
            alert("$msg");
            window.location.replace("index.php");
        </script>
        </body>
        </html> 
        EOT;
    }
?>