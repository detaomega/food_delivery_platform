<?php
    session_start();
    $dbservername = "localhost"; 
    $dbname = "DB_HW"; 
    $dbusername = "dev"; 
    $dbpassword = "devpasswd";
    if (!isset($_SESSION['Authenticated']) || $_SESSION['Authenticated'] != true) {
        header("Location: index.php");
        exit();
    }

    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select * from user where account=:account");
    $stmt->execute(array("account" => $_SESSION["account"]));
    $row = $stmt->fetch();
    $userLongitude = $row["position_longitude"];
    $userLatitude = $row["position_latitude"];
    $stmt = $conn->prepare("select * from store where UID=:UID");
    $stmt->execute(array("UID" => $row["ID"]));
    $shopRow = $stmt->fetch();
    echo <<< EOT
      <script>
        window.onload = function(){
          data = { 
            "shopSearch": "",
            "distSearch": "",
            "priceLow": "",
            "priceHigh": "",
            "mealSearch": "",
            "categorySearch": "",
            "userLongitude": "$userLongitude",
            "userLatitude": "$userLatitude"
          };
          console.log(data);
          $.post("search_shop.php", data, function(msg) {
            msg = JSON.parse(msg);
            for (var key in msg){
              if (key === "error" || key === "text") continue;
              msg[key] = JSON.parse(msg[key]);
            }
            if (msg.error) {
              alert(msg.text);
            } else {
              var cnt = 1;
              var table = document.getElementById("shopList");
                table.innerHTML = "<thead><tr><th scope="+"col"+">#</th><th scope="+"col"+">shop name</th><th scope="+"col"+">shop category</th><th scope="+"col"+">Distance</th></tr></thead><tbody></tbody>";
              for (var key in msg){
                if (key === "error") continue;
                var row = table.insertRow(-1);
                $(row).append('<th scope="row" id="row' + cnt.toString() + '">' + cnt.toString() + '</th>');
                $(row).append('<td>' + msg[key].name +'</td>');
                $(row).append('<td>' + msg[key].category +'</td>');
                $(row).append('<td>' + msg[key].Distance +'</td>');
                $(row).append('<td>' + msg[key].name +'</td>');
                $(row).append('<td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#' + msg[key].name + '">Open menu</button></td>');
                cnt++;
              }
              }
          });
        };
      </script>
    EOT;
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Hello, world!</title>
</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">Eats</a>
        <button type="button " style="margin-top: 8px; margin-left: auto; position: absolute; right: 1%;" class=" btn btn-info " data-toggle="modal" id="signOutBtn">Sign Out</button>
      </div>

    </div>
  </nav>
  <div class="container">

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1">shop</a></li>


    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
            Account: <?php echo $row["account"]; ?>, name: <?php echo $row["name"]; ?>, PhoneNumber: <?php echo $row["phone_number"]; ?>,  location: <?php echo $row["position_longitude"]; ?>, <?php echo $row["position_latitude"]; ?>
            
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!--  -->
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label " for="longitude">longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="enter longitude">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="locationEditBtn">Edit</button>
                  </div>
                </div>
              </div>
            </div>



            <!--  -->
            walletbalance: <?php echo $row["wallet"]; ?>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add value</button>
            <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add value</h4>
                  </div>
                  <div class="modal-body">
                    <input type="text" class="form-control" id="value" placeholder="enter add value">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="walletEditBtn">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <hr>
        <!-- 
                
             -->
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" id="searchForm">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Enter Shop name" id="shopSearch">
              </div>
              <label class="control-label col-sm-1" for="distance">distance</label>
              <div class="col-sm-5">


                <select class="form-control" id="distSearch">
                  <option></option>
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>

                </select>
              </div>

            </div>

            <div class="form-group">

              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">

                <input type="text" class="form-control" id="priceLow">

              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">

                <input type="text" class="form-control" id="priceHigh">

              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="mealSearch" placeholder="Enter Meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" id="categorySearch" placeholder="Enter shop category">
                  <datalist id="categories">
                    <option value="fast food">
               
                  </datalist>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary" id="searchBtn">Search</button>
              
            </div>
          </form>
        </div>

        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;" id="shopList">
            </table>

                <!-- Modal -->
  <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                <tr>
                  <th scope="row">1</th>
                  <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                
                  <td>Hamburger</td>
                
                  <td>80 </td>
                  <td>20 </td>
              
                  <td> <input type="checkbox" id="cbox1" value="Hamburger"></td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
                 
                  <td>coffee</td>
             
                  <td>50 </td>
                  <td>20</td>
              
                  <td><input type="checkbox" id="cbox2" value="coffee"></td>
                </tr>

              </tbody>
            </table>
          </div>

        </div>
        

         <!--  -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
        </div>
      </div>
      
    </div>
  </div>
          </div>

        </div>
      </div>
      <div id="menu1" class="tab-pane fade">
        <?php
        if ($row["status"] == "normal_user") echo <<<EOT
        <h3> Start a business </h3>
        <div class="form-group ">
          <div class="row">
            <div class="col-xs-2">
              <label for="shopName">shop name</label>
              <input class="form-control" id="shopName" placeholder="macdonald" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="shopCategory">shop category</label>
              <input class="form-control" id="shopCategory" placeholder="fast food" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="shopLatitude">latitude</label>
              <input class="form-control" id="shopLatitude" placeholder="24.78472733371133" type="text" >
            </div>
            <div class="col-xs-2">
              <label for="shopLongitude">longitude</label>
              <input class="form-control" id="shopLongitude" placeholder="121.00028167648875" type="text" >
            </div>
          </div>
        </div>
        <div class=" row" style=" margin-top: 25px;">
          <div class=" col-xs-3">
            <button type="button" class="btn btn-primary"  id="registerBtn">register</button>
          </div>
        </div>
        EOT;
        else echo <<< EOT
        <h3> Your shop </h3>
        <div class="form-group ">
          <div class="row">
            <div class="col-xs-2">
              <label for="shopName">shop name</label>
              <h3> {$shopRow["name"]} </h3>
            </div>
            <div class="col-xs-2">
              <label for="shopCategory">shop category</label>
              <h3> {$shopRow["category"]} </h3>
            </div>
            <div class="col-xs-2">
              <label for="shopLatitude">latitude</label>
              <h3> {$shopRow["position_latitude"]} </h3>
            </div>
            <div class="col-xs-2">
              <label for="shopLongitude">longitude</label>
              <h3> {$shopRow["position_longitude"]} </h3>
            </div>
          </div>
        </div>
        <hr>
        <h3>ADD</h3>
        <form action = "add_meal.php" Method="POST" Enctype="multipart/form-data">
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-6">
                <label for="ex3">meal name</label>
                <input class="form-control" = "ex3" type="text" name = "mealName">
              </div>
            </div>
            <div class="row" style=" margin-top: 15px;">
              <div class="col-xs-3">
                <label for="ex7">price</label>
                <input class="form-control" id="ex7" type="text" name = "mealPrice">
              </div>
              <div class="col-xs-3">
                <label for="ex4">quantity</label>
                <input class="form-control" id = "ex4" name = "mealQuantity" type="text">
              </div>
            </div>
            <div class="row" style=" margin-top: 25px;">
              <div class=" col-xs-3">
                <label for="ex12">上傳圖片</label>
                <input type="file" name="mealPicture">
              </div>
              <div class=" col-xs-3">
                <button style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
              </div>
            </div>
          </div>
        <form>
     
        EOT;
        if ($row["status"] == "owner") {
          include ("list_product.php");
        }
        ?>
       
      
      </div>



    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $("#searchForm").submit(function(e) {
          e.preventDefault();
      });
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
      $("#signOutBtn").click(function () {
        $.post("logout.php");
        window.location.replace("index.php");
      });
      $("#locationEditBtn").click(function () {
        var latitude = $("#latitude").val(), longitude = $("#longitude").val();
        data = { 
          "latitude": latitude,
          "longitude": longitude
        };
        $.post("edit_position.php", data, function(msg) {
          msg = JSON.parse(msg);
          if (msg.error) {
            alert(msg.text);
          } else {
            window.location.reload();
          }
        });
      });
      $("#walletEditBtn").click(function () {
        var value = $("#value").val();
        data = { "value": value };
        $.post("edit_wallet.php", data, function(msg) {
          msg = JSON.parse(msg);
          if (msg.error) {
            alert(msg.text);
          } else {
            window.location.reload();
          }
        });
      });
      $("#registerBtn").click(function() {
        var showName = $("#shopName").val(), shopCategory = $("#shopCategory").val(), shopLatitude = $("#shopLatitude").val(), shopLongitude = $("#shopLongitude").val();
        var userID = "<?php echo $row["ID"]; ?>", userPhone = "<?php echo $row["phone_number"]; ?>";
        data = {
          "shopName": showName,
          "shopCategory": shopCategory,
          "shopLatitude": shopLatitude,
          "shopLongitude": shopLongitude,
          "userID": userID,
          "userPhone": userPhone
        };
        console.log(data);
        $.post("register_shop.php", data, function(msg) {
          console.log(msg);
          msg = JSON.parse(msg);
          if (msg.error) {
            alert(msg.text);
            console.log(msg.text);
          } else {
            alert("Successfully registered!");
            window.location.reload();
          }
        });
      });
      $("#searchBtn").click(function () {
        var shopSearch = $("#shopSearch").val(), distSearch = $("#distSearch").val(), priceLow = $("#priceLow").val(), priceHigh = $("#priceHigh").val(), mealSearch = $("#mealSearch").val(), categorySearch = $("#categorySearch").val();
        var userLongitude = "<?php echo $userLongitude; ?>";
        var userLatitude = "<?php echo $userLatitude; ?>";
        data = { 
          "shopSearch": shopSearch,
          "distSearch": distSearch,
          "priceLow": priceLow,
          "priceHigh": priceHigh,
          "mealSearch": mealSearch,
          "categorySearch": categorySearch,
          "userLongitude": userLongitude,
          "userLatitude": userLatitude
        };
        console.log(data);
        $.post("search_shop.php", data, function(msg) {
          msg = JSON.parse(msg);
          for (var key in msg){
            if (key === "error" || key === "text") continue;
            msg[key] = JSON.parse(msg[key]);
          }
          if (msg.error) {
            alert(msg.text);
          } else {
            var cnt = 1;
            var table = document.getElementById("shopList");
              table.innerHTML = "<thead><tr><th scope="+"col"+">#</th><th scope="+"col"+">shop name</th><th scope="+"col"+">shop category</th><th scope="+"col"+">Distance</th></tr></thead><tbody></tbody>";
            for (var key in msg){
              if (key === "error") continue;
              var row = table.insertRow(-1);
              $(row).append('<th scope="row" id="row' + cnt.toString() + '">' + cnt.toString() + '</th>');
              $(row).append('<td>' + msg[key].name +'</td>');
              $(row).append('<td>' + msg[key].category +'</td>');
              $(row).append('<td>' + msg[key].Distance +'</td>');
              $(row).append('<td>' + msg[key].name +'</td>');
              $(row).append('<td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#' + msg[key].name + '">Open menu</button></td>');
              cnt++;
            }
          }
        });
      });
    });
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>