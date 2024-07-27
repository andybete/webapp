<?php
@include("headOfAdmin");

$con = mysqli_connect("localhost", "root", "", "exam");
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['check'])) {
        $selectedCenters = $_POST['check'];

        foreach ($selectedCenters as $email => $status) {
            if ($status === 'checked') {
                $centerData = explode('|', $email);
                $city = $centerData[0];
                $institution = $centerData[1];
                $department = $centerData[2];
                $level = $centerData[3];

                $updateSql = "UPDATE centers SET approve = 1 WHERE list_city = '$city' AND list_institution = '$institution' AND list_department = '$department' AND level = '$level'";
                $updateResult = mysqli_query($con, $updateSql);

                if ($updateResult) {
                    $selectSql = "SELECT * FROM centers WHERE list_city = '$city' AND list_institution = '$institution' AND list_department = '$department' AND level = '$level'";
                    $selectResult = mysqli_query($con, $selectSql);
                    $center = mysqli_fetch_assoc($selectResult);

                    if ($center) {
                        $city = $center['list_city'];
                        $institution = $center['list_institution'];
                        $level = $center['level'];
                        echo "<script>alert('Successfully approved center city: " . $city . ", institution: " . $institution . " with level: " . $level . "');</script>";
                    } else {
                        $msg = "Error: Center not found.<br>";
                    }
                } else {
                    $msg = "Error in updating center: " . mysqli_error($con) . "<br>";
                }
            }
        }
    }
}

$sql = "SELECT * FROM centers WHERE approve = 0";
$result = mysqli_query($con, $sql);
$count = 1;
?>

<head>
  <link rel="stylesheet" type="text/css" href="notification.css" />
</head>

<div id="container">
    <h2 align="center">List of new registered centers<hr></h2>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>City</th>
                    <th>Institution</th>
                    <th>Department</th>
                    <th>Level</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row['list_city']; ?></td>
                        <td><?php echo $row['list_institution']; ?></td>
                        <td><?php echo $row['list_department']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><input type="checkbox" name="check[<?php echo $row['list_city'] . '|' . $row['list_institution'] . '|' . $row['list_department'] . '|' . $row['level']; ?>]" value="checked"></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
		
		<?php echo "<br>"; echo $msg;  ?>
        <button type="submit" name="approve" class="submit-button">Approve</button>
    </form>
</div>