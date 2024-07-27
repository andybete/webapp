<?php
$institution = $_GET['institution'] ?? '';
$city = $_GET['city'] ?? '';
@include("headOfSchool");
$con = mysqli_connect("localhost", "root", "", "exam");

// Fetch the data from the centers table, including only approved centers
$sql = "SELECT * FROM centers WHERE list_city = '$city' AND list_institution = '$institution' AND approve = 1";
$result = mysqli_query($con, $sql);

if (!$result) {
    die('Error: ' . mysqli_error($con));
}

$count = 1;

?>

<head>
  <link rel="stylesheet" type="text/css" href="admin.css" />
</head>

<div id="container">
  <form method="POST">
    <h2 align="center"> VIEW CENTER LISTS <hr></h2>
    <?php //if (mysqli_num_rows($result) > 0) { ?>
	<?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <fieldset>
        <legend>Informations</legend>
        <div class="infos">
          <span>City:</span><?php echo $row['list_city']; ?><br>
          <span>Institution:</span><?php echo $row['list_institution']; ?><br>
          <span>Phone Number:</span><?php echo $row['phone_number']; ?><br>
          <span>Email:</span><?php echo $row['email']; ?><br>
          <span>Address:</span><?php echo $row['address']; ?><br>
        </div>
      </fieldset>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Department</th>
            <th>Level</th>
          </tr>
        </thead>
        <tbody>
		  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $row['list_department']; ?></td>
            <td><?php echo $row['level']; ?></td>
          </tr>
		  <?php } ?>
        </tbody>
      </table>
    <?php }?>
  </form>
</div>