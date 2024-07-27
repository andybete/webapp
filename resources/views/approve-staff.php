<?php
include("headOfAdmin.php");

$con = mysqli_connect("localhost", "root", "", "exam");
$msg = "";

if (isset($_POST['approve'])) {
    if (isset($_POST['check'])) {
        $checkedItems = $_POST['check'];
        foreach ($checkedItems as $item) {
            $data = explode('|', $item);
            $username = $data[0];
            $approvedDate = date('Y-m-d');

            $updateSql = "UPDATE staff SET activate = 'activated', approved_date = '$approvedDate' WHERE username = '$username'";
            mysqli_query($con, $updateSql);
        }
        echo "<script>alert('Successfully approved');</script>";
    }
}

$sql = "SELECT * FROM staff WHERE activate = 'processed'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result); // Get the number of rows

?>

<head>
    <link rel="stylesheet" type="text/css" href="notification.css" />
</head>

<div id="container">
    <h2 align="center">List of new registered centers<hr></h2>

    <?php if ($count > 0) { ?> <!-- Check if there are users in the table -->
        <form method="POST" action="">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>First name</th>
                        <th>Father name</th>
                        <th>Last name</th>
                        <th>Gender</th>
                        <th>Phone no</th>
                        <th>Email</th>
                        <th>Date of birth</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['first_name']; ?></td>
                            <td><?php echo $row['father_name']; ?></td>
                            <td><?php echo $row['last_name']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['date_of_birth']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['city']; ?></td>
                            <td><input type="checkbox" name="check[]" value="<?php echo $row['username']?>"></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php echo "<br>"; echo $msg;  ?>
            <button type="submit" name="approve" class="submit-button">Approve</button>
        </form>
    <?php } else { ?>
		 <script>alert('No new staff are in a table.');</script>
    <?php } ?>

</div>
<?php include("footer.php"); ?>