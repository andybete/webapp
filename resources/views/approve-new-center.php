<?php
@include("headOfAdmin");

$con = mysqli_connect("localhost", "root", "", "exam");
$msg = "";

if (isset($_POST['approve'])) {
    if (isset($_POST['check'])) {
        $checkedItems = $_POST['check'];
        foreach ($checkedItems as $item) {
            $data = explode('|', $item);
            $username = $data[0];
            $password = $data[1];
            $city = $data[2];
            $institution = $data[3];
            $approvedDate = date('Y-m-d');

            $updateSql = "UPDATE new_center SET approve = 1, approved_date = '$approvedDate' WHERE username = '$username' AND password = '$password' AND city = '$city' AND institution = '$institution'";
            mysqli_query($con, $updateSql);
        }
        echo "<script>alert('Successfully approved');</script>";
    }
}
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
                    <th>Username</th>
                    <th>Password</th>
                    <th>City</th>
                    <th>Institution</th>
                    <th>Registration date</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM new_center WHERE approve = 0";
                $result = mysqli_query($con, $sql);
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)):
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['institution']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><input type="checkbox" name="check[]" value="<?php echo $row['username'] . '|' . $row['password'] . '|' . $row['city'] . '|' . $row['institution']; ?>"></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php echo "<br>"; echo $msg;  ?>
        <button type="submit" name="approve" class="submit-button">Approve</button>
    </form>
</div>