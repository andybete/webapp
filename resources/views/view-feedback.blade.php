<?php
@include("headOfAdmin");
$con = mysqli_connect("localhost", "root", "", "exam");

// Fetch the data from the feedback table, grouping by name, email, and comment
$sql = "SELECT DISTINCT name, email, comment, date FROM feedback";
$result = mysqli_query($con, $sql);
?>

<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('admin.css') }}" />
</head>

<div id="container">
  <form method="POST">
    <table>
      <thead>
	    <tr>
	       <th colspan="5">Feedbacks of the users.</th>
		</tr>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Comment</th>
		  <th>Date of commented</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['comment']; ?></td>
			<td><?php echo $row['date']; ?></td>
          </tr>
          <?php
          $count++;
        }
        ?>
      </tbody>
    </table>
  </form>
</div>
<?php
 include("footer.php");
?>