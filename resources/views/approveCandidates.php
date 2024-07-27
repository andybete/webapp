<?php
@include("headOfAdmin");

$con = mysqli_connect("localhost", "root", "", "exam");


function generateCandidateId($con, $institution)
{
    $institution = mysqli_real_escape_string($con, $institution);
    $prefix = substr($institution, 0, 3);
    $sql = "SELECT MAX(candidate_Id) AS maxId FROM approveCandidate WHERE candidate_Id LIKE '$prefix%'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $maxId = $row['maxId'];

    if ($maxId) {
        $candidateId = $prefix . str_pad((int) substr($maxId, 3) + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $candidateId = $prefix . '00001';
    }

    return $candidateId;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['check'])) {
        $selectedCandidates = $_POST['check'];

        foreach ($selectedCandidates as $username => $status) {
            if ($status === 'checked') {
                $sql = "SELECT * FROM candidate";
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($candidate = mysqli_fetch_assoc($result)) {
                        $candidateId = generateCandidateId($con, $candidate['institution']);
                        
						// Set the desired timezone
                       $timezone = new DateTimeZone('UTC'); // Replace 'UTC' with the desired timezone

                        // Create a new DateTime object with the current date and the desired timezone
                       $currentDateTime = new DateTime('now', $timezone);

                       // Get the current year, month, and day
                       $currentYear = $currentDateTime->format('Y');
                       $currentMonth = $currentDateTime->format('m');
                       $currentDay = $currentDateTime->format('d');
						
						$insertSql = "INSERT INTO approvecandidate (candidate_Id, username, password, first_name, father_name, last_name, gender, phone_number, email, Date_Of_Birth, age, address, city, institution, department, level, gc_year, grade_report, approved_date) VALUES ('$candidateId', '{$candidate['username']}', '{$candidate['password']}', '{$candidate['first_name']}', '{$candidate['father_name']}', '{$candidate['last_name']}', '{$candidate['gender']}', '{$candidate['phone_number']}', '{$candidate['email']}', '{$candidate['Date_Of_Birth']}', '{$candidate['age']}', '{$candidate['address']}', '{$candidate['city']}', '{$candidate['institution']}', '{$candidate['department']}', '{$candidate['level']}', '{$candidate['gc_year']}', '{$candidate['grade_report']}', '$currentYear-$currentMonth-$currentDay')";
                        $insertResult = mysqli_query($con, $insertSql);
                        if ($insertResult) {
                            $deleteSql = "DELETE FROM candidate WHERE username = '$username'";
                            $deleteResult = mysqli_query($con, $deleteSql);

                            if (!$deleteResult) {
                                echo "Error in deleting candidate: " . mysqli_error($con);
                            }
                        } else {
                            echo "Error in inserting data into approveCandidate table: " . mysqli_error($con);
                        }
                    }
                } else {
                    echo "Error in fetching candidate data: " . mysqli_error($con);
                }
            }
        }
    }
}

$sql = "SELECT * FROM candidate";
$result = mysqli_query($con, $sql);
$count = 1;
?>


<html>
<head>
  <link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>
   <div id="container">
   <?php if(mysqli_num_rows($result) > 0) {?>
   <h2 align="center"> NEW CANDIDATE REQUESTS <hr></h2>
    <form method="POST">
        <table>
            <thead>
			 <tr>
                <th>no</th>
				<th>User name </th>
				<th>Password </th>
                <th>Name</th>
                <th>father_name</th>
                <th>last_name</th>
                <th>gender</th>
                <th>phone_number</th>
                <th>email</th>
                <th>date_of_birth</th>
                <th>age</th>
				<th>city</th>
                <th>institution</th>
                <th>department</th>
                <th>level</th>
                <th>gc_year</th>
                <th>grade_report</th>
                <th>address</th>
                <th>remark</th>
             </tr> 
			</thead>
            <tbody>
			   
			   <?php while ($row = mysqli_fetch_assoc($result)): ?>
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
				 <td><?php echo $row['Date_Of_Birth']; ?></td>
				 <td><?php echo $row['age']; ?></td>
				 <td><?php echo $row['city']; ?></td>
				 <td><?php echo $row['institution']; ?></td>
				 <td><?php echo $row['department']; ?></td>
				 <td><?php echo $row['level']; ?></td>
				 <td><?php echo $row['gc_year']; ?></td>
				 <td><?php echo "<a href='" . $row['grade_report'] . "'>View photo</a>"; ?></td>
				 <td><?php echo $row['address']; ?></td>
				 <td><input type="checkbox" name="check[<?php echo $row['username'] ?>]" value="checked"></td>
                </tr>
				
			<?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="approve"class="submit-button">Approve</button>
    </form>
	</div>
	<?php } 
	else {
	echo "<script>alert('No new registered candidates.'); window.location.href = 'admin.php';</script>";
    }?>

	<?php mysqli_close($con); ?>
	
</body>
</html>
<?php include("footer.php"); ?>
				 
				 
