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
    $UID = $row["ID"];
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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
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
      <li><a href="#myOrder">MyOrder</a></li>
      <li><a href="#shopOrder">Shop Order</a></li>
      <li><a href="#record">Trasaction Record</a></li>


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
        <div class="row   col-xs-8">
          <hr>
          <h3>Results</h3>
          <table class="table" style=" margin-top: 15px;" id="shopList">
          </table>
          <div class="row   col-xs-8" id="shopModal">
          </div>

        </div>
      </div>
      <div id="menu1" class="tab-pane fade">
        <?php
        if ($row["status"] == "normal_user") {
          echo <<<EOT
          <h3> Start a business </h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="shopName">shop name</label>
                <input class="form-control" id="shopName" placeholder="macdonald" type="text" >
                <div id="shopNameCheck" style="float: left; color: red;"></div>
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
        }
        else {
          echo <<< EOT
          <h3> Start a business </h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="shopName">shop name</label>
                <input class="form-control" id="shopName" placeholder="{$shopRow["name"]}" type="text" disabled>
              </div>
              <div class="col-xs-2">
                <label for="shopCategory">shop category</label>
                <input class="form-control" id="shopCategory" placeholder="{$shopRow["category"]}" type="text" disabled>
              </div>
              <div class="col-xs-2">
                <label for="shopLatitude">latitude</label>
                <input class="form-control" id="shopLatitude" placeholder="{$shopRow["position_latitude"]}" type="text" disabled>
              </div>
              <div class="col-xs-2">
                <label for="shopLongitude">longitude</label>
                <input class="form-control" id="shopLongitude" placeholder="{$shopRow["position_longitude"]}" type="text" disabled>
              </div>
            </div>
          </div>
          <div class=" row" style=" margin-top: 25px;">
            <div class=" col-xs-3">
              <button type="button" class="btn btn-primary" disabled>register</button>
            </div>
          </div>
          <hr>
          <h3>ADD</h3>
  
          
          <form action = "add_meal.php" Method="POST" Enctype="multipart/form-data">
            <div class="form-group ">
              <div class="row">
                <div class="col-xs-6">
                  <label for="ex3">meal name</label>
                  <input class="form-control" id = "ex3" type="text" name = "mealName">
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
          </form>
          
          EOT;
          include ("show_product.php");
        }
        ?>
       
      
      </div>
      <div id="myOrder" class="tab-pane fade">
        <?php include ("myOrderPage.php"); ?>
      </div>
      <div id="shopOrder" class="tab-pane fade">
      <?php include ("shopOrderPage.php"); ?>
      </div>
      <div id="record" class="tab-pane fade">
      </div>
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script>
    const sort_by = (field, reverse, primer) => {
      const key = primer ?
        function(x) {
          return primer(x[field])
        } :
        function(x) {
          return x[field]
        };

      reverse = !reverse ? 1 : -1;

      return function(a, b) {
        return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
      }
    }
    $(document).ready(function () {
      var shops = [];
      $("#shopList").DataTable({
        "searching": false,
        "lengthChange": false,
        "pageLength": 5,
        "destroy": true,
        "data": shops,
        "columns": [ //列的標題一般是從DOM中讀取（也可以使用這個屬性為表格創建列標題)
        { data: 'number', title: "#" },
        { data: 'name', title: "shop name" },
        { data: 'category', title: "shop category" },
        { data: 'distance', title: "Distance" },
        { data: 'distanceValue', title: "distanceValue" },
        { data: 'button', title: "" }
        ],
        "columnDefs": [
          {
            'orderData':[4], 
            'targets': [3] 
          },
          {
            "targets": [5],
            "orderable": false
          },
          {
            "targets": [4],
            "visible": false
          }
        ]
      });
      var data = { 
        "shopSearch": "",
        "distSearch": "",
        "priceLow": "",
        "priceHigh": "",
        "mealSearch": "",
        "categorySearch": "",
        "userLongitude": "<?php echo $userLongitude; ?>",
        "userLatitude": "<?php echo $userLatitude; ?>"
      };
      var searchShopFunc = function(msg) {
        msg = JSON.parse(msg);
        for (var key in msg){
          if (key === "error" || key === "text") continue;
          msg[key] = JSON.parse(msg[key]);
        }
        if (msg.error) {
          alert(msg.text);
        } else {
          shops = [];
          var cnt = 1;
          var modal = document.getElementById("shopModal");
          var UID = "<?php echo $UID; ?>"
          modal.innerHTML = "";
          for (var key in msg){
            if (key === "error") continue;
            var s = "";
            s = '<td> <button type="button" class="btn btn-info " data-toggle="modal" data-target="#shopList' + msg[key].ID + '">Open menu</button></td>';
            shops.push({
              "number": cnt,
              "name": msg[key].name,
              "category": msg[key].category,
              "distance": msg[key].Distance,
              "distanceValue": msg[key].distanceValue,
              "button": s
            });
            data = { "SID": msg[key].ID, 
                     "distance": msg[key].distanceValue,
                     "UID": UID
            };
          
            $.post("list_show_product.php", data, function(msg2) {
              modal.innerHTML += msg2;
            });
            cnt++;
          }
          $('#shopList').DataTable().clear().rows.add(shops).draw();
        }
      }
      $.post("search_shop.php", data, searchShopFunc);
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
            alert("Recharge success");
            window.location.reload();
          }
        });
      });
      $("#shopName").keyup(function(){
				var input = $(this).val();
				data = { 
					"input": input
				};
				$.post("shopNameCheck.php", data, function(msg) {
					msg = JSON.parse(msg);
					if (msg.error) {
						alert(msg.text);
					} else {
						if (msg.used) {
							$("#shopNameCheck").html("The name is taken.");
						} else {
							$("#shopNameCheck").html("");
						}
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
        $.post("register_shop.php", data, function(msg) {
          msg = JSON.parse(msg);
          if (msg.error) {
            alert(msg.text);
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
        $.post("search_shop.php", data, searchShopFunc);
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