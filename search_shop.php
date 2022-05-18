<?php
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

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
        
        $condp = "where store.ID = SID and ";
        $cond = "where 1 = 1 and ";
        $condArray = array();
        if ($shopSearch != "") {
            $cond = $cond . $nameCond . " and ";
            $condp = $condp . $nameCond . " and ";
            $condArray += ["shopSearch" => $shopSearch];
        }
        if ($priceLow != "") {
            $cond = $cond . $priceLowCond . " and ";
            $condp = $condp . $priceLowCond . " and ";
            $condArray += ["priceLow" => $priceLow];
        }
        if ($priceHigh != "") {
            $cond = $cond . $priceHighCond . " and ";
            $condp = $condp . $priceHighCond . " and ";
            $condArray += ["priceHigh" => $priceHigh];
        }
        if ($mealSearch != "") {
            $cond = $cond . $mealCond . " and ";
            $condp = $condp . $mealCond . " and ";
            $condArray += ["mealSearch" => $mealSearch];
        }
        if ($categorySearch != "") {
            $cond = $cond . $categoryCond . " and ";
            $condp = $condp . $categoryCond . " and ";
            $condArray += ["categorySearch" => $categorySearch];
        }
        $cond = substr($cond, 0, strlen($cond) - 4);
        $condp = substr($condp, 0, strlen($condp) - 4);
        if ($priceLow != "" || $priceHigh != "" || $mealSearch != "") {
            $stmt=$conn->prepare("select * from store, product " . $condp . " group by store.name");
            $stmt->execute($condArray);
            $res = ["error" => false];
            $i = 0;
            while ($row = $stmt->fetch()) {
                $dist = distance($row["position_latitude"], $row["position_longitude"], $_POST["userLatitude"], $_POST["userLongitude"], "K");
                if ($distSearch == "near" && $dist <= 2) {
                    $row += ["Distance" => "near"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == "medium" && $dist <= 5 && $dist > 2) {
                    $row += ["Distance" => "medium"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == "far"  && $dist > 5) {
                    $row += ["Distance" => "far"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == ""){
                    if ($dist <= 2) {
                        $row += ["Distance" => "near"];
                    } else if ($dist <= 5 && $dist > 2) {
                        $row += ["Distance" => "medium"];
                    } else if ($dist > 5) {
                        $row += ["Distance" => "far"];
                    }
                    $res += [$i => json_encode($row)];
                    $i++;
                }
            }
            if (count($res) == 1) {
                throw new Exception("No results.");
            }
            echo json_encode($res) . "\n";
        } else {
            $stmt=$conn->prepare("select * from store " . $cond . " group by store.name");
            $stmt->execute($condArray);
            $res = ["error" => false];
            $i = 0;
            while ($row = $stmt->fetch()) {
                $dist = distance($row["position_latitude"], $row["position_longitude"], $_POST["userLatitude"], $_POST["userLongitude"], "K");
                if ($distSearch == "near" && $dist <= 2) {
                    $row += ["Distance" => "near"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == "medium" && $dist <= 5 && $dist > 2) {
                    $row += ["Distance" => "medium"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == "far"  && $dist > 5) {
                    $row += ["Distance" => "far"];
                    $res += [$i => json_encode($row)];
                    $i++;
                } else if ($distSearch == ""){
                    if ($dist <= 2) {
                        $row += ["Distance" => "near"];
                    } else if ($dist <= 5 && $dist > 2) {
                        $row += ["Distance" => "medium"];
                    } else if ($dist > 5) {
                        $row += ["Distance" => "far"];
                    }
                    $res += [$i => json_encode($row)];
                    $i++;
                }
            }
            if (count($res) == 1) {
                throw new Exception("No results.");
            }
            echo json_encode($res) . "\n";
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("error" => true, "text" => $msg));
    }
    
?>