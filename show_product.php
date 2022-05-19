<div class="row">
  <div class="  col-xs-8">
    <table class="table" style=" margin-top: 15px;">
      <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Picture</th>
        <th scope="col">meal name</th>
      
        <th scope="col">price</th>
        <th scope="col">Quantity</th>
        <th scope="col">Edit</th>
        <th scope="col">Delete</th>
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
          $stmt -> execute(array("UID" => $UID));
          $row = $stmt -> fetch();
          $SID = $row["ID"];

          $stmt = $conn -> prepare("select * from product where SID=:SID");
          $stmt -> execute(array("SID" => $SID));
          $result = $stmt -> fetchAll();
          $cnt=1;
          foreach ($result as &$row) {
            $ID = $row['ID'];
            $name = $row['name'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $picture = $row["image"];
            $picture_type = $row['picture_type'];
            $img=$row["image"];
            $logodata = $img;

            echo '<tr><td>'.$cnt.'</td><td><img style="max-width:50%; max-height:100px" src="data:'.$picture_type.';base64,' . $picture . '"  alt="$name"/></td>';
            echo <<< EOT
                <td>$name</td>
                <td>$price</td>
                <td>$quantity</td>
                <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#$ID">Edit</button></td>
                <!-- Modal -->
                <div class="modal fade" id="$ID" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">$name Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="edit_product.php" method="post">
                        <div class="modal-body">
                          <div class="row" >
                            <div class="col-xs-6">
                              <label for="ex71">Price</label>
                              <input class="form-control" id="ex71" name="productPrice" type="text">
                            </div>
                            <div class="col-xs-6">
                              <label for="ex41">Quantity</label>
                              <input class="form-control" id="ex41" name="productQuantity" type="text">
                            </div>
                            <input type="hidden" name="ID" value="$ID">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-secondary">Edit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <form action="del_product.php" method="post">
                  <input type="hidden" name="ID" value="$ID">
                  <td><button type="submit" class="btn btn-danger">Delete</button></td>
                </form>
              </tr>
            EOT;
            $cnt++;
          }
        ?>
      </tbody>
    </table>
  </div>
</div>