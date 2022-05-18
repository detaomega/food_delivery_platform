<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $SID = $_POST["SID"];

    $stmt = $conn -> prepare("select * from product where SID=:SID");
    $stmt -> execute(array("SID" => $SID));
    $result = $stmt -> fetchAll();
    $cnt=1;
    echo <<< EOT
            <!-- Modal -->
            <div class="modal fade" id="shopList$SID"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">menu</h4>
                </div>
                <div class="modal-body">
                <!--  -->
            
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
                        
                            <th scope="col">Order check</th>
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
                        
                            <td>$price </td>
                            <td>$quantity </td>
                        
                            <td> <input type="checkbox" id="cbox1" value="$ID"></td>
                        </tr>
                        EOT;
        $cnt++;
    }  
    echo <<< EOT
            </tbody>
            </table>
            </div>

        </div>


        <!--  -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="orderBtn">Order</button>
        </div>
        </div>

        </div>
        </div>
    EOT;
?>