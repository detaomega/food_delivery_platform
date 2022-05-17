<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $emptyField = "";
        
        // check wheather is empty
        if (empty($_POST["mealName"])) $emptyField = $emptyField . "meal name, ";
        if (empty($_POST["mealPrice"])) $emptyField = $emptyField . "meal price, ";
        if (empty($_POST["mealQuantity"])) $emptyField = $emptyField . "meal quantity, ";
        if (empty($_FILES["mealPicture"]["tmp_name"])) $emptyField = $emptyField . "please choose a meal picture, ";
        // if (empty($_POST["shopLongitude"])) $emptyField = $emptyField . "longitude, ";
        
        $mealName = $_POST["mealName"];
        $mealPrice = $_POST["mealPrice"];
        $mealQuantity = $_POST["mealQuantity"];
    

        if (!empty($emptyField)) {
            
            $emptyField = substr($emptyField, 0, strlen($emptyField) - 2) . " ";
            throw new Exception($emptyField . "should not be empty.");
        }

        // check price is none negative
        if (!preg_match('/^\d+$/', $mealPrice)) {
            throw new Exception("the meal price show be none negative");
        }

        // check quantity is none negative
        if (!preg_match('/^\d+$/', $mealQuantity)) {
            throw new Exception("the meal quantity show be none negative");
        }

        $stmt = $conn -> prepare("select ID from user where account=:account");
        $stmt -> execute(array("account" => $_SESSION["account"]));
        $row = $stmt->fetch();
         
        $UID = $row["ID"];

        $stmt = $conn -> prepare("select ID from store where UID=:UID");
        $stmt -> execute(array("UID" => $UID));
        $row = $stmt -> fetch();
        $SID = $row["ID"];

       
        $file = fopen($_FILES["mealPicture"]["tmp_name"], "rb");
        // 讀入圖片檔資料
        $fileContents = fread($file, filesize($_FILES["mealPicture"]["tmp_name"])); 
        //關閉圖片檔
        fclose($file);
        //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
        $fileContents = base64_encode($fileContents);
        //組合查詢字串
        $imgType = $_FILES["mealPicture"]["type"];
        $msg = $fileContents;
        $stmt = $conn->prepare (
            "insert into product (
                name, 
                image, 
                price, 
                quantity, 
                SID,
                picture_type
            ) 
            values (
                :name, 
                :image, 
                :price, 
                :quantity, 
                :SID,
                :picture_type
            )"
        );

        $stmt -> execute(
            array (
                "name" => $mealName, 
                "image" => $fileContents, 
                "price" => $mealPrice, 
                "quantity" => $mealQuantity,
                "SID" => $SID,
                "picture_type" => $imgType
            )
        );
        header('Location: nav.php#menu1');

    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#menu1\");</script>";
    }
?>
