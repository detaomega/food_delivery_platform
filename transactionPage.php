<label class="control-label col-sm-1" for="type">Filter</label>   
<div class="col-sm-4">
    <select class="form-control" name="mode" id="transaction_slt1">
        <option value="#transaction_tab_1">All</option>
        <option value="#transaction_tab_2">Payment</option>
        <option value="#transaction_tab_3">Takings</option>
        <option value="#transaction_tab_4">Recharge</option>
    </select>
</div>
<div class="row" id="transaction_tab_1">
    <div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Record ID</th>
                    <th scope="col">Action</th>
                    <th scope="col">Time</th>           
                    <th scope="col">Trader</th>
                    <th scope="col">Amount changed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    session_start();
                    $dbservername = "localhost"; 
                    $dbname = "DB_HW"; 
                    $dbusername = "dev"; 
                    $dbpassword = "devpasswd";
                    
                    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $conn -> prepare("select ID from user where account=:account");
                    $stmt -> execute(array("account" => $_SESSION["account"]));
                    $row = $stmt->fetch();
                    $UID = $row["ID"];
                    $account = $_SESSION["account"];
                    
                    $stmt = $conn -> prepare("select * from transaction where UID = :UID");
                    $stmt -> execute(array("UID" => $UID));
                    $result = $stmt -> fetchAll();
                    $cnt=1;
                    foreach ($result as &$row) {
                        $TID = $row['ID'];
                        $type = $row['type'];
                        $time = $row['time'];
                        $price = $row['price'];
                        $UID = $row['UID'];
                        $TUID = $row['target_UID'];
                        $stmt = $conn -> prepare("select name from store where UID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $shopInfo = $stmt->fetch();
                        $shopName = $shopInfo['name'];
                        $stmt = $conn -> prepare("select account from user where ID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $targetInfo = $stmt->fetch();
                        $targetAccount = $targetInfo["account"];

                        echo <<< EOT
                            <tr>
                                <th scope="row">$cnt</th>
                                <td>$TID</td>
                                <td>$type</td>
                                <td>$time</td>
                                
                        EOT;
                        if ($type == "Payment") {
                            echo <<< EOT
                                <td>$shopName</td>
                                <td>-$price</td>
                            EOT;
                        } else if ($type == "Takings"){
                            echo <<< EOT
                                <td>$targetAccount</td>
                                <td>+$price</td>
                            EOT;
                        } else {
                            echo <<< EOT
                                <td>$account</td>
                                <td>+$price</td>
                            EOT;
                        }
                        echo <<< EOT
                            </tr>
                        EOT;
                        $cnt++;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row" id="transaction_tab_2">
<div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Record ID</th>
                    <th scope="col">Action</th>
                    <th scope="col">Time</th>           
                    <th scope="col">Trader</th>
                    <th scope="col">Amount changed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    session_start();
                    $dbservername = "localhost"; 
                    $dbname = "DB_HW"; 
                    $dbusername = "dev"; 
                    $dbpassword = "devpasswd";
                    
                    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $conn -> prepare("select ID from user where account=:account");
                    $stmt -> execute(array("account" => $_SESSION["account"]));
                    $row = $stmt->fetch();
                    $UID = $row["ID"];
                    $account = $_SESSION["account"];
                    
                    $stmt = $conn -> prepare("select * from transaction where UID = :UID");
                    $stmt -> execute(array("UID" => $UID));
                    $result = $stmt -> fetchAll();
                    $cnt=1;
                    foreach ($result as &$row) {
                        $TID = $row['ID'];
                        $type = $row['type'];
                        if ($type != "Payment") continue;
                        $time = $row['time'];
                        $price = $row['price'];
                        $UID = $row['UID'];
                        $TUID = $row['target_UID'];
                        $stmt = $conn -> prepare("select name from store where UID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $shopInfo = $stmt->fetch();
                        $shopName = $shopInfo['name'];
                        $stmt = $conn -> prepare("select account from user where ID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $targetInfo = $stmt->fetch();
                        $targetAccount = $targetInfo["account"];

                        echo <<< EOT
                            <tr>
                                <th scope="row">$cnt</th>
                                <td>$TID</td>
                                <td>$type</td>
                                <td>$time</td>
                                
                        EOT;
                        if ($type == "Payment") {
                            echo <<< EOT
                                <td>$shopName</td>
                                <td>-$price</td>
                            EOT;
                        } else if ($type == "Takings"){
                            echo <<< EOT
                                <td>$targetAccount</td>
                                <td>+$price</td>
                            EOT;
                        } else {
                            echo <<< EOT
                                <td>$account</td>
                                <td>+$price</td>
                            EOT;
                        }
                        echo <<< EOT
                            </tr>
                        EOT;
                        $cnt++;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row" id="transaction_tab_3">
<div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Record ID</th>
                    <th scope="col">Action</th>
                    <th scope="col">Time</th>           
                    <th scope="col">Trader</th>
                    <th scope="col">Amount changed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    session_start();
                    $dbservername = "localhost"; 
                    $dbname = "DB_HW"; 
                    $dbusername = "dev"; 
                    $dbpassword = "devpasswd";
                    
                    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $conn -> prepare("select ID from user where account=:account");
                    $stmt -> execute(array("account" => $_SESSION["account"]));
                    $row = $stmt->fetch();
                    $UID = $row["ID"];
                    $account = $_SESSION["account"];
                    
                    $stmt = $conn -> prepare("select * from transaction where UID = :UID");
                    $stmt -> execute(array("UID" => $UID));
                    $result = $stmt -> fetchAll();
                    $cnt=1;
                    foreach ($result as &$row) {
                        $TID = $row['ID'];
                        $type = $row['type'];
                        if ($type != "Takings") continue;
                        $time = $row['time'];
                        $price = $row['price'];
                        $UID = $row['UID'];
                        $TUID = $row['target_UID'];
                        $stmt = $conn -> prepare("select name from store where UID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $shopInfo = $stmt->fetch();
                        $shopName = $shopInfo['name'];
                        $stmt = $conn -> prepare("select account from user where ID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $targetInfo = $stmt->fetch();
                        $targetAccount = $targetInfo["account"];

                        echo <<< EOT
                            <tr>
                                <th scope="row">$cnt</th>
                                <td>$TID</td>
                                <td>$type</td>
                                <td>$time</td>
                                
                        EOT;
                        if ($type == "Payment") {
                            echo <<< EOT
                                <td>$shopName</td>
                                <td>-$price</td>
                            EOT;
                        } else if ($type == "Takings"){
                            echo <<< EOT
                                <td>$targetAccount</td>
                                <td>+$price</td>
                            EOT;
                        } else {
                            echo <<< EOT
                                <td>$account</td>
                                <td>+$price</td>
                            EOT;
                        }
                        echo <<< EOT
                            </tr>
                        EOT;
                        $cnt++;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row" id="transaction_tab_4">
<div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Record ID</th>
                    <th scope="col">Action</th>
                    <th scope="col">Time</th>           
                    <th scope="col">Trader</th>
                    <th scope="col">Amount changed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    session_start();
                    $dbservername = "localhost"; 
                    $dbname = "DB_HW"; 
                    $dbusername = "dev"; 
                    $dbpassword = "devpasswd";
                    
                    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $conn -> prepare("select ID from user where account=:account");
                    $stmt -> execute(array("account" => $_SESSION["account"]));
                    $row = $stmt->fetch();
                    $UID = $row["ID"];
                    $account = $_SESSION["account"];
                    
                    $stmt = $conn -> prepare("select * from transaction where UID = :UID");
                    $stmt -> execute(array("UID" => $UID));
                    $result = $stmt -> fetchAll();
                    $cnt=1;
                    foreach ($result as &$row) {
                        $TID = $row['ID'];
                        $type = $row['type'];
                        if ($type != "Recharge") continue;
                        $time = $row['time'];
                        $price = $row['price'];
                        $UID = $row['UID'];
                        $TUID = $row['target_UID'];
                        $stmt = $conn -> prepare("select name from store where UID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $shopInfo = $stmt->fetch();
                        $shopName = $shopInfo['name'];
                        $stmt = $conn -> prepare("select account from user where ID=:TUID");
                        $stmt -> execute(array("TUID" => $TUID));
                        $targetInfo = $stmt->fetch();
                        $targetAccount = $targetInfo["account"];

                        echo <<< EOT
                            <tr>
                                <th scope="row">$cnt</th>
                                <td>$TID</td>
                                <td>$type</td>
                                <td>$time</td>
                                
                        EOT;
                        if ($type == "Payment") {
                            echo <<< EOT
                                <td>$shopName</td>
                                <td>-$price</td>
                            EOT;
                        } else if ($type == "Takings"){
                            echo <<< EOT
                                <td>$targetAccount</td>
                                <td>+$price</td>
                            EOT;
                        } else {
                            echo <<< EOT
                                <td>$account</td>
                                <td>+$price</td>
                            EOT;
                        }
                        echo <<< EOT
                            </tr>
                        EOT;
                        $cnt++;
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>



<script>
    $(function() {
        $('div[id^="transaction_tab_2"]').hide();
        $('div[id^="transaction_tab_3"]').hide();
        $('div[id^="transaction_tab_4"]').hide();
        $('#transaction_slt1').change(function() {
            let sltValue=$(this).val();
            console.log(sltValue);
            $('div[id^="transaction_tab_"]').hide();
            $(sltValue).show();
        });  
    });
</script>
