<label class="control-label col-sm-1" for="type">action</label>   
<div class="col-sm-4">
    <select class="form-control" name="mode" id="slt1">
        <option value="#tab_1">All</option>
        <option value="#tab_2">Finished</option>
        <option value="#tab_3">Not Finish</option>
        <option value="#tab_4">Cancel</option>
    </select>
</div>
<div class="row" id="tab_1">
    <div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start</th>           
                    <th scope="col">End</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Order Details</th>
                    <th scope="col"> Action</th>
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

                    $stmt = $conn -> prepare("select ID from store where UID=:UID");
                    $stmt -> execute(array("UID" => $UID]));
                    $row = $stmt->fetch();
                    $SID = $row["ID"];
                    
                    $stmt = $conn -> prepare("select * from `order` where SID=:SID");
                    $stmt -> execute(array("SID" => $SID));
                    $result = $stmt -> fetchAll();
                    $cnt=1;
                    foreach ($result as &$row) {
                        $OID = $row['ID'];
                        $status = $row['status'];
                        $start_time = $row['start_time'];
                        $finish_time = $row['finish_time'];
                        $payment = $row['payment'];
                        $SID = $row['SID'];
                        $stmt = $conn -> prepare("select name from `store` where ID=:SID");
                        $stmt -> execute(array("SID" => $SID));
                        $shopInfo = $stmt -> fetch();
                        $shopName = $shopInfo['name'];

                        echo <<< EOT
                            <tr>
                                <th scope="row">$cnt</th>
                                <td>$status</td>
                                <td>$start_time</td>
                                <td>$end_time</td>
                                <td>$shopName</td>
                                <td>$payment</td>
                                <td><button type="button" class="btn btn-info" data-toggle="modal">Order Details</button></td>
                                
                        EOT;
                        if ($status == "Not finish") {
                            echo <<< EOT
                                <form action="calcel_user_order.php" method="post">
                                    <input type="hidden" name="OID" value="$OID">
                                    <td><button type="submit" class="btn btn-danger">Cancel</button></td>
                                </form>
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

<div class="row" id="tab_2">
    <div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start</th>           
                    <th scope="col">End</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Order Details</th>
                    <th scope="col"> Action</th>
                </tr>
            </thead>
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
                $stmt = $conn -> prepare("select ID from store where UID=:UID");
                $stmt -> execute(array("UID" => $UID]));
                $row = $stmt->fetch();
                $SID = $row["ID"];
                
                $stmt = $conn -> prepare("select * from `order` where SID=:SID");
                $stmt -> execute(array("SID" => $SID));
                $result = $stmt -> fetchAll();
                $cnt=1;
                foreach ($result as &$row) {
                    $status = $row['status'];
                    $start_time = $row['start_time'];
                    $finish_time = $row['finish_time'];
                    $payment = $row['payment'];
                    $SID = $row['SID'];
                    $stmt = $conn -> prepare("select name from `store` where ID=:SID");
                    $stmt -> execute(array("SID" => $SID));
                    $shopInfo = $stmt -> fetch();
                    $shopName = $shopInfo['name'];

                    echo <<< EOT
                        <tr>
                            <th scope="row">$cnt</th>
                            <td>$status</td>
                            <td>$start_time</td>
                            <td>$end_time</td>
                            <td>$shopName</td>
                            <td>$payment</td>
                            <td><button type="button" class="btn btn-info" data-toggle="modal">Order Details</button></td>
                        </tr>
                    EOT;
                    $cnt++;
                }
            ?>
        </table>
    </div>
</div>

<div class="row" id="tab_3">
    <div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start</th>           
                    <th scope="col">End</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Order Details</th>
                    <th scope="col"> Action</th>
                </tr>
            </thead>
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
                $stmt = $conn -> prepare("select ID from store where UID=:UID");
                $stmt -> execute(array("UID" => $UID]));
                $row = $stmt->fetch();
                $SID = $row["ID"];
                
                $stmt = $conn -> prepare("select * from `order` where SID=:SID");
                $stmt -> execute(array("SID" => $SID));
                $result = $stmt -> fetchAll();
                $cnt=1;
                foreach ($result as &$row) {
                    $status = $row['status'];
                    $start_time = $row['start_time'];
                    $finish_time = $row['finish_time'];
                    $payment = $row['payment'];
                    $SID = $row['SID'];
                    $stmt = $conn -> prepare("select name from `store` where ID=:SID");
                    $stmt -> execute(array("SID" => $SID));
                    $shopInfo = $stmt -> fetch();
                    $shopName = $shopInfo['name'];

                    echo <<< EOT
                        <tr>
                            <th scope="row">$cnt</th>
                            <td>$status</td>
                            <td>$start_time</td>
                            <td>$end_time</td>
                            <td>$shopName</td>
                            <td>$payment</td>
                            <td><button type="button" class="btn btn-info" data-toggle="modal">Order Details</button></td>
                            <td><button type="submit" class="btn btn-danger">Cancel</button></td>
                        </tr>
                    EOT;
                    $cnt++;
                }
            ?>
        </table>
    </div>
</div>

<div class="row" id="tab_4">
    <div class="col-xs-8">
        <table class="table" style=" margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start</th>           
                    <th scope="col">End</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Order Details</th>
                    <th scope="col"> Action</th>
                </tr>
            </thead>
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
                $stmt = $conn -> prepare("select ID from store where UID=:UID");
                $stmt -> execute(array("UID" => $UID]));
                $row = $stmt->fetch();
                $SID = $row["ID"];
                
                $stmt = $conn -> prepare("select * from `order` where SID=:SID");
                $stmt -> execute(array("SID" => $SID));
                $result = $stmt -> fetchAll();
                $cnt=1;
                foreach ($result as &$row) {
                    $status = $row['status'];
                    $start_time = $row['start_time'];
                    $finish_time = $row['finish_time'];
                    $payment = $row['payment'];
                    $SID = $row['SID'];
                    $stmt = $conn -> prepare("select name from `store` where ID=:SID");
                    $stmt -> execute(array("SID" => $SID));
                    $shopInfo = $stmt -> fetch();
                    $shopName = $shopInfo['name'];
                    echo <<< EOT
                        <tr>
                            <th scope="row">$cnt</th>
                            <td>$status</td>
                            <td>$start_time</td>
                            <td>$end_time</td>
                            <td>$shopName</td>
                            <td>$payment</td>
                            <td><button type="button" class="btn btn-info" data-toggle="modal">Order Details</button></td>

                        </tr>
                    EOT;
                    $cnt++;
                }
            ?>
        </table>
    </div>
</div>



<script>
    $(function() {
        $('div[id^="tab_2"]').hide();
        $('div[id^="tab_3"]').hide();
        $('div[id^="tab_4"]').hide();
        $('#slt1').change(function() {
            let sltValue=$(this).val();
            console.log(sltValue);
            $('div[id^="tab_"]').hide();
            $(sltValue).show();
        });  
    });
</script>