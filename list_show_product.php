<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $SID = $_POST["SID"];

    $stmt = $conn -> prepare("select version from store where ID=:SID");
    $stmt -> execute(array("SID" => $SID));
    $storeInfo = $stmt -> fetch();
    $version = $storeInfo["version"];
    
    $stmt = $conn -> prepare("select * from product where SID=:SID");
    $stmt -> execute(array("SID" => $SID));
    $result = $stmt -> fetchAll();
    $distance = $_POST["distance"];
    $UID =$_POST["UID"];
    $cnt = 1;
    echo <<< EOT
            <!-- Modal -->
            <div class="modal fade" id="shopList$SID"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
            
                <!-- Modal content-->
                
                    <div class="modal-content">
                    <form action="count_price.php" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">menu</h4>
                        </div>
                        <div class="modal-body">            
                            <div class="row">
                                <div class="  col-xs-12">
                                    <table class="table" style=" margin-top: 15px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Picture</th>
                                            
                                                <th scope="col">meal name</th>
                                            
                                                <th scope="col">price</th>
                                                <th scope="col">Quantity</th>
                                            
                                                <th scope="col">order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    EOT;
    foreach ($result as &$row) {
        $ID = $row['ID'];
        $name = $row['name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $picture = $row["image"];
        $picture_type = $row['picture_type'];
        $img=$row["image"];
        $logodata = $img;

        echo <<< EOT
                                            <tr>
                                                <th scope="row">$cnt</th>
                                                <td><img style="max-width:50%; max-height:100px" src="data:$picture_type;base64,$picture"  alt="$name"/></td>
                                                <td>$name</td>
                                            
                                                <td>$price</td>
                                                <td>$quantity</td>
                                                <td><input type="number" name="$ID" value=0 min=0 max="$quantity"></td>
                                            </tr>
        EOT;
        $cnt++;
    }  
    echo <<< EOT
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <button type="submit"  class="btn btn-default" id="orderBtn">Order</button>
                            <label class="control-label col-sm-1" for="type">Type</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="mode">
                                    <option>Delivery</option>
                                    <option>Pick-up</option>
                                </select>
                            </div>
                            <input type="hidden" name="SID" value="$SID">
                            <input type="hidden" name="distance" value="$distance">
                            <input type="hidden" name="UID" value="$UID">
                            <input type="hidden" name="version" value="$version">
                        </div>
                    </form>
                    </div>
                </div>
            </div>
    EOT;
?>