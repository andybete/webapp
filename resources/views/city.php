<?php
$con = mysqli_connect("localhost", "root", "", "exam");
$msg = $vcity = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = $_POST['list_city'];
    if (empty($city)) {
        $msg = "City is required";
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z ]*$/", $city)) {
        $msg = "Only alphabets are allowed";
    } else {
        $vcity = $city; // Store the validated city value in $vcity

        $query = "SELECT * FROM city_list WHERE list_city = '$vcity'";
        $result = mysqli_query($con, $query);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            $msg = "The data already exists in the table.";
        } else {
            $insertQuery = "INSERT INTO city_list (list_city) VALUES ('$vcity')";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                echo "<script>alert('The city has been successfully inserted into the table.'); window.location.href = 'admin.php';</script>";
            } else {
                $msg = "Failed to insert the data.";
            }
        }
    }
}
if (isset($_POST['submit2'])){
	$department = $_POST['list_department'];
    if (empty($department)) {
        $msg = "department is required";
    } else if (!preg_match("/^[a-zA-Z][a-zA-Z ]*$/", $department)) {
        $msg = "Only alphabets are allowed";
    } else {
        $vdepartment = $department; // Store the validated city value in $vcity

        $query = "SELECT * FROM department_list WHERE list_department = '$vdepartment'";
        $result = mysqli_query($con, $query);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            $msg = "The data already exists in the table.";
        } else {
            $insertQuery = "INSERT INTO department_list (list_department) VALUES ('$vdepartment')";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                echo "<script>alert('The department has been successfully inserted into the table.'); window.location.href = 'admin.php';</script>";
            } else {
                $msg = "Failed to insert the data.";
            }
        }
    }
}
?>
<body>
 <?php include("headOfAdmin.php");?>
    <div id="container">
	<div class="list">
	   <h3> Welcome To Admin Page  </h3>
	   <h2> SOUTH ETHIOPIA Occupational <br> Competency Accreditation Agency </h2>
	   <select class="but" onchange="toggleForm(this)">
	       <option disabled selected>Insert Actions</option>
	       <option value="insert_city.php">Insert City</option>
		   <option value="insert_department.php">Insert department</option>
	   </select>
	</div>
	<div id="insert-city" style="display: none;">
     <div class="form_container">
     <!--insert user account form-->
     <h4>Insert new city</h4>
      <div class="form">
        <form method="post">
           City:<input type="text" name="list_city"><br><br>
		   <input type="submit" name="submit" value="submit"><br>
		   <?php echo"$msg";?>
		 </form>
      </div>
    </div>
    </div>
    <div id="insert-dep" style="display: none;">
     <div class="form_container">
     <!--insert user account form-->
     <h4>Insert new department</h4>
	   <div class="form">
        <form method="post">
            Department:<input type="text" name="list_department"><br><br>
		   <input type="submit" name="submit2" value="submit"><br>
		   <?php echo"$msg";?>
        </form>
      </div>
    </div>
    </div>
	<div id="cont_list">
     <h2> SOUTH ETHIOPIA COC Agency </h2>
	 <p> This is admin home page. To insert new city of center and new departments please first select from option then insert your datas  </p>
  </div>
</div><br>
<div id="last"></div>
<?php include("footer.php");?>

<script>
function toggleForm(selectElement) {
    var formInsertCity = document.getElementById("insert-city");
    var formInsertDep = document.getElementById("insert-dep")
    var contList = document.getElementById("cont_list");

    if (selectElement.value === "insert_city.php") {
        formInsertCity.style.display = "block";
        formInsertDep.style.display = "none";
        contList.style.display = "none";
    } else if (selectElement.value === "insert_department.php") {
        formInsertCity.style.display = "none";
        formInsertDep.style.display = "block";
        contList.style.display = "none";
    }else {
        formInsertCity.style.display = "none";
        formInsertDep.style.display = "none";
        contList.style.display = "block";
    }
}
</script>
 
	