<?php

$institution = $_GET['institution'] ?? '';
$city = $_GET['city'] ?? '';
?>
<?php
//include("headOfSchool.php");

$con = mysqli_connect("localhost", "root", "", "exam");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['check'])) {
        $selectedCandidates = $_POST['check'];

        foreach ($selectedCandidates as $username => $status) {
            if ($status === 'checked') {
                //$sql = "SELECT * FROM school_candidate";
				$sql = "SELECT * FROM school_candidate WHERE institution = '$institution' AND city = '$city' AND approve = 0";
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($candidate = mysqli_fetch_assoc($result)) {
                        
					$insertSql = "INSERT INTO Candidate (username, password, first_name, father_name, last_name, gender, phone_number, email, Date_Of_Birth, age, address, city, institution, department, level, gc_year, grade_report) VALUES ('{$candidate['username']}', '{$candidate['password']}', '{$candidate['first_name']}', '{$candidate['father_name']}', '{$candidate['last_name']}', '{$candidate['gender']}', '{$candidate['phone_number']}', '{$candidate['email']}', '{$candidate['Date_Of_Birth']}', '{$candidate['age']}', '{$candidate['address']}', '{$candidate['city']}', '{$candidate['institution']}', '{$candidate['department']}', '{$candidate['level']}', '{$candidate['gc_year']}', '{$candidate['grade_report']}')";
                     $insertResult = mysqli_query($con, $insertSql);

                        if ($insertResult) {
							$updateSql = "UPDATE school_candidate SET approve = 1";
                            //$deleteSql = "DELETE FROM candidate WHERE username = '$username'";
                            $updateResult = mysqli_query($con, $updateSql);

                            if (!$updateResult) {
                                echo "Error in updating candidate: " . mysqli_error($con);
                            }
                        } else {
                            echo "Error in inserting data into Candidate table: " . mysqli_error($con);
                        }
                    }
                } else {
                    echo "Error in fetching candidate data: " . mysqli_error($con);
                }
            }
        }
    }
}


$sql = "SELECT * FROM school_candidate WHERE institution = '$institution' AND city = '$city' AND approve = 0";
$result = mysqli_query($con, $sql);
$count = 1;
?>


<html>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
  <link rel="stylesheet" type="text/css" href="notification.css" />
</head>
<body>
   <div id="header1">
     <img width="100%" height="100px" src="images/sam1.png">
	</div>
    <div id="navigation1">
	  <?php
	
    // Define the page hierarchy
    $pages = array(
        'Home' => 'school.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
        'approve candidate' => 'school_approve_candidate.php?institution=' . urlencode($_GET['institution']) . '&city=' . urlencode($_GET['city']),
    );

    // Get the current page
    $current_page = basename($_SERVER['PHP_SELF']);

    // Generate the page hierarchy
    $page_hierarchy = '<a href="' . $pages['Home'] . '">Home</a>';
	

    if ($current_page != 'school.php' && isset(array_flip($pages)[$current_page])) {
        $page_hierarchy .= ' >> <a href="' . $pages[array_flip($pages)[$current_page]] . '">' . array_flip($pages)[$current_page] . '</a>';
    }

    // Output the page hierarchy
    echo $page_hierarchy;
    ?>
	</div>
   
   <div id="site_content1">
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
                <th>phone_no</th>
                <th>email</th>
                <th>date_of_birth</th>
                <th>age</th>
				<th>address</th>
				<th>city</th>
                <th>institution</th>
                <th>dep.</th>
                <th>level</th>
                <th>gc_year</th>
                <th>grade_report</th>
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
				 <td><?php echo $row['address']; ?></td>
				 <td><?php echo $row['city']; ?></td>
				 <td><?php echo $row['institution']; ?></td>
				 <td><?php echo $row['department']; ?></td>
				 <td><?php echo $row['level']; ?></td>
				 <td><?php echo $row['gc_year']; ?></td>
				 <td><?php echo "<a href='" . $row['grade_report'] . "'>View photo</a>"; ?></td>
				 <td><input type="checkbox" name="check[<?php echo $row['username'] ?>]" value="checked"></td>
                </tr>
				
			<?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="approve"class="submit-button">Approve</button>
    </form>
	</div>
	<?php } else {
    //'<p class="message">No new registered candidates.</p>';
    echo "<script>alert('No new registered candidates.'); window.location.href = 'school.php?institution=" . urlencode($institution) . "&city=" . urlencode($city) . "';</script>";
    }?>
	<?php mysqli_close($con); ?>
	
</body>
</html>
<?php include("footer.php");?>
				 
				 
