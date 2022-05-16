<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        $shopSearch = $_POST["shopSearch"];
        $distSearch = $_POST["distSearch"];
        $priceLow = $_POST["priceLow"];
        $priceHigh = $_POST["priceHigh"];
        $mealSearch = $_POST["mealSearch"];
        $categorySearch = $_POST["categorySearch"];

        if ($_POST["priceLow"] != "" && !preg_match('/^[0-9]+$/', $_POST["priceLow"])) {
            throw new Exception("Please make sure the price value is an integer.");
        }
        if ($_POST["priceHigh"] != "" && !preg_match('/^[0-9]+$/', $_POST["priceHigh"])) {
            throw new Exception("Please make sure the price value is an integer.");
        }
        
        $nameCond = "lower(store.name) LIKE CONCAT('%',:shopSearch,'%')" ;
        $priceLowCond = "price >= :priceLow" ;
        $priceHighCond = "price <= :priceHigh";
        $mealCond = "lower(product.name) LIKE CONCAT('%',:mealSearch,'%')" ;
        $categoryCond = "lower(category) LIKE CONCAT('%',:categorySearch,'%')" ;
        
        $cond = "where store.ID = SID and ";
        $condArray = array();
        if ($shopSearch != "") {
            $cond = $cond . $nameCond . " and ";
            $condArray += ["shopSearch" => $shopSearch];
        }
        if ($priceLow != "") {
            $cond = $cond . $priceLowCond . " and ";
            $condArray += ["priceLow" => $priceLow];
        }
        if ($priceHigh != "") {
            $cond = $cond . $priceHighCond . " and ";
            $condArray += ["priceHigh" => $priceHigh];
        }
        if ($mealSearch != "") {
            $cond = $cond . $mealCond . " and ";
            $condArray += ["mealSearch" => $mealSearch];
        }
        if ($categorySearch != "") {
            $cond = $cond . $categoryCond . " and ";
            $condArray += ["categorySearch" => $categorySearch];
        }
        $cond = substr($cond, 0, strlen($cond) - 4);
        $stmt=$conn->prepare("select store.name, category from store, product " . $cond);
        $stmt->execute($condArray);
        $res = ["error" => false];
        $i = 0;
        while ($row = $stmt->fetch()) {
            $res += [$i => json_encode($row)];
            $i++;
        }
        if (count($res) == 1) {
            throw new Exception("No results.");
        }
        echo json_encode($res) . "\n";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
    
?>